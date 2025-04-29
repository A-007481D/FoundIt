import { defineStore } from 'pinia';
import axios from 'axios';
import * as tf from '@tensorflow/tfjs';
import * as mobilenet from '@tensorflow-models/mobilenet';

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
        
        // Load MobileNet model for feature extraction
        await tf.ready();
        const model = await mobilenet.load();
        
        this.model = model;
        this.isModelLoading = false;
        
        return model;
      } catch (error) {
        this.errorMessage = 'Failed to load image recognition model. Please try again.';
        this.searchStatus = 'error';
        this.isModelLoading = false;
        console.error('Error loading TensorFlow model:', error);
        throw error;
      }
    },
    
    async processImage(imageFile) {
      try {
        this.searchStatus = 'analyzing';
        this.isProcessing = true;
        this.searchResults = [];
        
        // Load model if not loaded
        const model = this.model || await this.loadModel();
        
        // Convert the file to an image element
        const imageElement = await this.fileToImage(imageFile);
        
        // Extract features using MobileNet
        const activation = await model.infer(imageElement, true);
        
        // Extract more specific attributes using MobileNet classifications
        const classifications = await model.classify(imageElement);
        
        // Get feature vectors as array
        const featureVector = Array.from(activation.dataSync());
        
        // Analyze the image for category and color
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
        this.errorMessage = 'Error processing image. Please try again.';
        this.searchStatus = 'error';
        this.isProcessing = false;
        console.error('Error processing image:', error);
        throw error;
      }
    },
    
    analyzeImage(classifications) {
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
        
        this.searchResults = response.data.results;
        this.detectionResults.color = response.data.color || 'Unknown';
        this.detectionResults.brand = response.data.brand || 'Unknown';
        
        this.searchStatus = 'complete';
        return this.searchResults;
      } catch (error) {
        this.errorMessage = 'Error searching for similar items.';
        this.searchStatus = 'error';
        console.error('Error searching for similar items:', error);
        throw error;
      } finally {
        this.isProcessing = false;
      }
    },
    
    fileToImage(file) {
      return new Promise((resolve, reject) => {
        const image = new Image();
        const url = URL.createObjectURL(file);
        
        image.onload = () => {
          resolve(image);
          URL.revokeObjectURL(url);
        };
        
        image.onerror = (error) => {
          reject(error);
          URL.revokeObjectURL(url);
        };
        
        image.src = url;
        image.width = 224;  // MobileNet expected size
        image.height = 224;
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