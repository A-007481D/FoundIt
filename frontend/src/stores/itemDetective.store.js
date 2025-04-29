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
    mode: 'simple',
    detectionResults: {
      category: null,
      color: null,
      brand: null,
      matchPercentage: 0
    },
    serverSideOnly: false, // Flag to bypass TensorFlow.js entirely
    tfErrorCount: 0, // Track TensorFlow.js errors
    maxTfErrors: 2  // After this many errors, switch to server-side only permanently
  }),
  
  actions: {
    async loadModel() {
      if (this.model) return this.model;
      
      // If we're in server-side only mode, don't attempt to load the model
      if (this.serverSideOnly) {
        console.log('Server-side only mode active, skipping model loading');
        return null;
      }
      
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
        this.errorMessage = 'Failed to load image recognition model. Using server-side processing instead.';
        this.serverSideOnly = true; // Switch to server-side only mode
        this.isModelLoading = false;
        
        // Don't throw the error, just return null to indicate server-side processing should be used
        return null;
      }
    },
    
    async processImage(imageFile) {
      try {
        this.searchStatus = 'analyzing';
        this.isProcessing = true;
        this.searchResults = [];
        this.errorMessage = null;
        
        console.log('Processing image...');
        
        // If server-side only mode is enabled or we've had too many TensorFlow errors, 
        // skip TensorFlow.js processing
        if (this.serverSideOnly || this.tfErrorCount >= this.maxTfErrors) {
          console.log('Using server-side only processing...');
          await this.serverSideProcessing(imageFile);
          return;
        }
        
        // Try TensorFlow.js processing with error handling
        try {
          // Load model if not loaded
          if (!this.model) {
            console.log('Model not loaded yet, loading...');
            const model = await this.loadModel();
            
            // If model loading failed, switch to server-side processing
            if (!model) {
              console.log('Model loading failed, switching to server-side...');
              this.tfErrorCount++;
              await this.serverSideProcessing(imageFile);
              return;
            }
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
          
          // Reset error count since TensorFlow.js worked
          this.tfErrorCount = 0;
          
          return {
            featureVector,
            classifications
          };
        } catch (tfError) {
          // Increment error count
          this.tfErrorCount++;
          console.log(`TensorFlow.js error (${this.tfErrorCount}/${this.maxTfErrors}), falling back to server-side`);
          
          // If we've had too many errors, permanently switch to server-side only
          if (this.tfErrorCount >= this.maxTfErrors) {
            console.log('Too many TensorFlow.js errors, permanently switching to server-side only mode');
            this.serverSideOnly = true;
          }
          
          // Fall back to server-side processing without showing error in console
          await this.serverSideProcessing(imageFile);
        }
      } catch (error) {
        // This is only for errors in the overall process or server-side fallback
        console.error('Error processing image:', error);
        this.errorMessage = 'Error processing image. Please try with a different image or browser.';
        this.searchStatus = 'error';
        this.isProcessing = false;
        throw error;
      }
    },
    
    async serverSideProcessing(imageFile) {
      try {
        this.searchStatus = 'searching';
        
        // Create form data
        const formData = new FormData();
        formData.append('image', imageFile);
        formData.append('mode', this.mode);
        
        // Call API endpoint without TensorFlow features
        const response = await axios.post('/api/item-detective/search', formData);
        
        // Process the response
        this.searchResults = response.data.results || [];
        this.detectionResults = {
          category: response.data.category || 'Unknown',
          color: response.data.color || 'Unknown',
          brand: response.data.brand || 'Unknown',
          matchPercentage: 85 // Default confidence level for server-side
        };
        
        this.searchStatus = 'complete';
        this.isProcessing = false;
        
        return this.searchResults;
      } catch (error) {
        console.error('Error in server-side processing:', error);
        this.errorMessage = 'Server error processing image. Please try again.';
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
        formData.append('mode', this.mode);
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
    
    setServerSideOnly(value) {
      this.serverSideOnly = value;
      if (value) {
        console.log('Switched to server-side only mode');
      } else {
        console.log('Switched to client-side TensorFlow.js mode');
      }
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
      // Don't reset serverSideOnly flag here
    }
  }
}); 