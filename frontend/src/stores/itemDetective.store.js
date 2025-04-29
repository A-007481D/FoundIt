import { defineStore } from 'pinia';
import axios from 'axios';
import * as tf from '@tensorflow/tfjs';

// Only load MobileNet when we need it (lazy loading)
let mobilenetModule = null;
const loadMobilenetModule = async () => {
  if (!mobilenetModule) {
    mobilenetModule = await import('@tensorflow-models/mobilenet');
  }
  return mobilenetModule;
};

export const useItemDetectiveStore = defineStore('itemDetective', {
  state: () => ({
    model: null,
    isModelLoading: false,
    isProcessing: false,
    searchResults: [],
    searchStatus: null, // 'idle', 'loading', 'analyzing', 'searching', 'complete', 'error'
    errorMessage: null,
    imageFeatures: null,
    detectionResults: {
      category: null,
      color: null,
      brand: null,
      matchPercentage: 0
    }
  }),
  
  actions: {
    async loadModel() {
      if (this.model) return this.model;
      
      try {
        this.isModelLoading = true;
        this.searchStatus = 'loading';
        
        // Ensure TensorFlow.js is ready
        console.log('Setting up TensorFlow.js...');
        await tf.ready();
        
        // Try to use WebGL, fall back to CPU if necessary
        if (tf.getBackend() !== 'webgl') {
          try {
            console.log('Trying to set WebGL backend...');
            await tf.setBackend('webgl');
          } catch (e) {
            console.warn('WebGL backend failed, using CPU fallback:', e);
            // If WebGL fails, CPU is usually the fallback
          }
        }
        
        console.log('Using TensorFlow backend:', tf.getBackend());
        
        // Dynamically import MobileNet to reduce initial load time
        const mobilenet = await loadMobilenetModule();
        
        // Load MobileNet model
        console.log('Loading MobileNet model...');
        const model = await mobilenet.load({
          version: 1,
          alpha: 0.25  // Use a smaller/faster model
        });
        
        console.log('MobileNet model loaded successfully');
        this.model = model;
        this.isModelLoading = false;
        
        return model;
      } catch (error) {
        console.error('Error loading TensorFlow model:', error);
        this.errorMessage = 'Failed to load image recognition model. Please try again in a different browser.';
        this.searchStatus = 'error';
        this.isModelLoading = false;
        throw error;
      }
    },
    
    async processImage(imageFile) {
      try {
        this.searchStatus = 'analyzing';
        this.isProcessing = true;
        this.searchResults = [];
        this.errorMessage = null;
        
        console.log('Processing image...');
        
        // Load model if not loaded
        if (!this.model) {
          console.log('Model not loaded yet, loading...');
          await this.loadModel();
        }
        
        // Convert the file to an image element
        console.log('Converting file to image element...');
        const imageElement = await this.fileToImage(imageFile);
        
        console.log('Running inference with MobileNet...');
        // First just try classification which is more reliable
        const classifications = await this.model.classify(imageElement);
        console.log('Classifications:', classifications);
        
        // Then try getting the feature vector
        console.log('Extracting feature vector...');
        const activation = await this.model.infer(imageElement, true);
        const featureVector = Array.from(activation.dataSync());
        console.log('Feature vector extracted, length:', featureVector.length);
        
        // Analyze the image for category and color based on classification
        this.analyzeImage(classifications);
        
        // Store feature vector for API call
        this.imageFeatures = {
          vector: featureVector,
          classifications: classifications,
          rawFile: imageFile
        };
        
        // Search for matching items
        await this.searchSimilarItems();
        
        return {
          featureVector,
          classifications
        };
      } catch (error) {
        console.error('Error processing image:', error);
        this.errorMessage = 'Error processing image. Please try with a different image or browser.';
        this.searchStatus = 'error';
        this.isProcessing = false;
        throw error;
      }
    },
    
    analyzeImage(classifications) {
      try {
        // Extract category information from classifications
        const topClass = classifications[0];
        
        // Color detection will be handled serverside
        // For now, we'll just use the classification confidence
        this.detectionResults = {
          category: topClass.className.split(',')[0].trim(),
          color: 'Processing...',
          brand: 'Processing...',
          matchPercentage: Math.round(topClass.probability * 100)
        };
      } catch (error) {
        console.error('Error analyzing image:', error);
        // Don't throw here, just log it
      }
    },
    
    async searchSimilarItems() {
      try {
        this.searchStatus = 'searching';
        
        if (!this.imageFeatures) {
          throw new Error('No image has been processed');
        }
        
        // Create form data
        const formData = new FormData();
        formData.append('image', this.imageFeatures.rawFile);
        formData.append('features', JSON.stringify(this.imageFeatures.vector));
        formData.append('classifications', JSON.stringify(this.imageFeatures.classifications));
        
        // Call API endpoint
        const response = await axios.post('/api/item-detective/search', formData);
        
        this.searchResults = response.data.results || [];
        this.detectionResults.color = response.data.color || 'Unknown';
        this.detectionResults.brand = response.data.brand || 'Unknown';
        
        this.searchStatus = 'complete';
        return this.searchResults;
      } catch (error) {
        console.error('Error searching for similar items:', error);
        this.errorMessage = 'Error searching for similar items.';
        this.searchStatus = 'error';
        throw error;
      } finally {
        this.isProcessing = false;
      }
    },
    
    fileToImage(file) {
      return new Promise((resolve, reject) => {
        console.log('Loading image from file...');
        const image = new Image();
        const url = URL.createObjectURL(file);
        
        // Set a timeout in case the image never loads
        const timeoutId = setTimeout(() => {
          URL.revokeObjectURL(url);
          reject(new Error('Image loading timed out after 15 seconds'));
        }, 15000);
        
        image.onload = () => {
          console.log('Image loaded successfully');
          clearTimeout(timeoutId);
          resolve(image);
          URL.revokeObjectURL(url);
        };
        
        image.onerror = (error) => {
          console.error('Error loading image:', error);
          clearTimeout(timeoutId);
          reject(new Error('Failed to load image'));
          URL.revokeObjectURL(url);
        };
        
        // Order matters here
        image.crossOrigin = 'anonymous';
        image.width = 224;  // MobileNet expected size
        image.height = 224;
        image.src = url;
      });
    },
    
    resetState() {
      this.searchResults = [];
      this.errorMessage = null;
      this.searchStatus = 'idle';
      this.imageFeatures = null;
      this.detectionResults = {
        category: null,
        color: null,
        brand: null,
        matchPercentage: 0
      };
    }
  }
}); 