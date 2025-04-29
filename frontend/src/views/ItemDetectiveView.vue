<template>
  <div class="item-detective-container">
    <!-- Header -->
    <div class="header-banner">
      <div class="search-bar">
        <span class="search-icon">
          <Search size="20" />
        </span>
        <h1>Item Detective</h1>
        <span class="subtitle">Advanced AI-powered lost item recovery</span>
      </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <div v-if="showUploader" class="upload-container">
        <div 
          class="dropzone"
          :class="{ 'active': isDragging, 'has-preview': imagePreview }"
          @drop.prevent="handleDrop"
          @dragover.prevent="isDragging = true"
          @dragleave.prevent="isDragging = false"
          @dragenter.prevent="isDragging = true"
          @click="$refs.fileInput.click()"
        >
          <input 
            type="file" 
            ref="fileInput"
            accept="image/*" 
            class="hidden"
            @change="handleFileSelect"
          />
          
          <img v-if="imagePreview" :src="imagePreview" class="image-preview" />
          
          <div v-else class="upload-prompts">
            <div class="upload-icon">
              <Upload size="48" />
            </div>
            <h2>Upload Item Photo</h2>
            <p>Drag and drop or click to browse</p>
            <span class="file-type-hint">JPEG, PNG, or WebP</span>
          </div>
        </div>
        
        <button 
          @click="startDetection" 
          :disabled="!imageFile || store.isProcessing"
          class="detect-button"
        >
          <Sparkles v-if="!store.isProcessing" size="20" />
          <Loader v-else size="20" class="animate-spin" />
          {{ store.isProcessing ? 'Processing...' : 'Start Detection' }}
        </button>
      </div>
      
      <!-- Processing View -->
      <div v-if="store.searchStatus === 'analyzing' || store.searchStatus === 'searching'" class="detection-progress">
        <div class="card">
          <img v-if="imagePreview" :src="imagePreview" class="item-card-image" />
          <div class="spinner">
            <Loader size="48" class="animate-spin" />
          </div>
          <h2>Detective is on the case!</h2>
          <p>Searching through thousands of found items using advanced pattern recognition.</p>
          
          <div class="progress-container">
            <div class="progress-label">
              <span>Searching Database</span>
              <span>{{ progressStage === 'analyzing' ? '50% Complete' : '75% Complete' }}</span>
            </div>
            <div class="progress-bar">
              <div 
                class="progress-fill" 
                :style="{ width: `${progressStage === 'analyzing' ? 50 : 75}%` }"
              ></div>
            </div>
          </div>
          
          <!-- AI Detection Results -->
          <div v-if="store.detectionResults.category" class="ai-detection-results">
            <h3>
              <Sparkles size="18" />
              AI Detection Results
            </h3>
            
            <div class="detection-result-item">
              <div class="label">Item Category</div>
              <div class="value-bar">
                <div class="bar-fill" :style="{ width: `${store.detectionResults.matchPercentage}%` }"></div>
              </div>
              <div class="value">{{ store.detectionResults.category }} ({{ store.detectionResults.matchPercentage }}%)</div>
            </div>
            
            <div class="detection-result-item">
              <div class="label">Color Analysis</div>
              <div class="value-bar">
                <div class="bar-fill" :style="{ width: '85%' }"></div>
              </div>
              <div class="value">{{ store.detectionResults.color }} (87%)</div>
            </div>
            
            <div class="detection-result-item">
              <div class="label">Brand Detection</div>
              <div class="value-bar">
                <div class="bar-fill" :style="{ width: '60%' }"></div>
              </div>
              <div class="value">{{ store.detectionResults.brand }}</div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Results View -->
      <div v-if="store.searchStatus === 'complete'" class="results-container">
        <div class="results-sidebar">
          <div class="source-image-container">
            <img v-if="imagePreview" :src="imagePreview" class="source-image" />
            <div class="ai-results">
              <h4>AI Analysis Results</h4>
              
              <div class="result-rows">
                <div class="result-row">
                  <span class="label">Category</span>
                  <div class="progress-bar">
                    <div class="progress-fill" :style="{ width: '100%' }"></div>
                  </div>
                  <span class="value">{{ store.detectionResults.category }}</span>
                </div>
                
                <div class="result-row">
                  <span class="label">Color</span>
                  <div class="progress-bar">
                    <div class="progress-fill" :style="{ width: '85%' }"></div>
                  </div>
                  <span class="value">{{ store.detectionResults.color }}</span>
                </div>
                
                <div class="result-row">
                  <span class="label">Size</span>
                  <div class="progress-bar">
                    <div class="progress-fill" :style="{ width: '75%' }"></div>
                  </div>
                  <span class="value">Medium</span>
                </div>
              </div>
              
              <button @click="resetDetection" class="search-again-btn">
                Search Again
              </button>
            </div>
            
            <div class="filter-section">
              <h4>Filter Results</h4>
              
              <div class="match-threshold">
                <div class="threshold-header">
                  <span>Match Threshold: {{ matchThreshold }}%</span>
                </div>
                <input 
                  type="range" 
                  v-model="matchThreshold" 
                  min="50" 
                  max="95" 
                  step="5" 
                  class="threshold-slider"
                />
              </div>
              
              <div class="categories">
                <div class="category-header">Categories</div>
                <div class="category-tags">
                  <div class="category-tag active">
                    <Circle size="16" /> All
                    <span class="count">159</span>
                  </div>
                  <div class="category-tag">
                    <Monitor size="16" /> Electronics
                    <span class="count">43</span>
                  </div>
                  <div class="category-tag">
                    <Watch size="16" /> Accessories
                    <span class="count">38</span>
                  </div>
                  <div class="category-tag">
                    <Briefcase size="16" /> Bags
                    <span class="count">27</span>
                  </div>
                  <div class="category-tag">
                    <Shirt size="16" /> Clothing
                    <span class="count">51</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="results-main">
          <div class="results-header">
            <h2>
              <Search size="20" /> 
              Detective Results
            </h2>
            <div class="result-count-badge">{{ store.searchResults.length }} Potential Matches</div>
          </div>
          
          <p class="results-description">
            We found {{ store.searchResults.length }} items that match your search criteria. Review the matches below.
          </p>
          
          <div class="results-grid">
            <div 
              v-for="(result, index) in store.searchResults" 
              :key="index" 
              class="result-card"
            >
              <div class="card-image-container">
                <img :src="result.image_url || '/placeholder-image.jpg'" class="card-image" />
                <div class="match-badge" :class="getMatchBadgeClass(result.match_percentage)">
                  {{ result.match_percentage }}% Match
                </div>
              </div>
              
              <div class="card-content">
                <h3 class="item-name">{{ result.name }}</h3>
                
                <div class="item-details">
                  <div class="detail-row">
                    <MapPin size="14" />
                    <span>Found at: {{ result.location }}</span>
                  </div>
                  
                  <div class="detail-row">
                    <Calendar size="14" />
                    <span>Date: {{ result.found_date }}</span>
                  </div>
                  
                  <div class="detail-row">
                    <Clock size="14" />
                    <span>Last seen: {{ result.last_seen }}</span>
                  </div>
                </div>
                
                <div class="item-tags">
                  <div class="tag">{{ result.color }} color</div>
                  <div v-if="result.feature1" class="tag">{{ result.feature1 }}</div>
                  <div v-if="result.feature2" class="tag">{{ result.feature2 }}</div>
                </div>
                
                <div class="card-actions">
                  <button class="contact-btn">Contact Finder</button>
                  <button class="more-btn">
                    More Details
                    <ChevronDown size="14" />
                  </button>
                </div>
              </div>
            </div>
            
            <!-- Show empty state if no results -->
            <div v-if="store.searchResults.length === 0" class="empty-results">
              <div class="empty-icon">
                <SearchX size="48" />
              </div>
              <h3>No Matches Found</h3>
              <p>We couldn't find any items matching your search criteria. Try adjusting your filters or uploading a clearer image.</p>
              <button @click="resetDetection" class="try-again-btn">
                Try Again
              </button>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Error State -->
      <div v-if="store.searchStatus === 'error'" class="error-container">
        <div class="error-icon">
          <AlertTriangle size="48" />
        </div>
        <h3>Something went wrong</h3>
        <p>{{ store.errorMessage || 'We encountered an error while processing your request. Please try again.' }}</p>
        <div class="mt-2 text-sm text-gray-500">
          <p>If the problem persists, please try:</p>
          <ul class="list-disc pl-5 mt-1">
            <li>Using a different image format (JPG or PNG)</li>
            <li>Using a smaller image</li>
            <li>Taking a clearer photo of the item</li>
            <li>Using Chrome or Edge browser</li>
          </ul>
        </div>
        <button @click="resetDetection" class="try-again-btn">
          Try Again
        </button>
      </div>
    </div>
  </div>
  
  <!-- Debug Panel -->
  <div class="debug-panel text-white" :class="{ 'expanded': showDebugPanel }">
    <div class="debug-panel-header" @click="showDebugPanel = !showDebugPanel">
      Debug Panel
      <span v-if="showDebugPanel" class="text-white rounded-md cursor-pointer bg-red-200 hover:bg-gray-500 px-2 py-1">Hide</span>
      <span v-else class="text-white rounded-md cursor-pointer bg-green-200 hover:bg-gray-500 px-2 py-1">Show</span>
    </div>
    
    <div v-if="showDebugPanel" class="debug-panel-content">
      <div class="debug-section">
        <h4>API Status</h4>
        <div class="debug-row">
          <span class="debug-label">Backend Connection:</span>
          <span class="debug-value" :class="{ 'connected': apiConnected, 'error': !apiConnected }">
            {{ apiConnected ? 'Connected' : 'Disconnected' }}
          </span>
        </div>
        <button @click="testConnection" class="debug-btn">Test Connection</button>
      </div>
      
      <div class="debug-section">
        <h4>Store State</h4>
        <div class="debug-row">
          <span class="debug-label text-white">Status:</span>
          <span class="debug-value text-white">{{ store.searchStatus }}</span>
        </div>
        <div class="debug-row">
          <span class="debug-label text-white">Results:</span>
          <span class="debug-value text-white">{{ store.searchResults.length }}</span>
        </div>
        <div class="debug-row">
          <span class="debug-label text-red-500">Error:</span>
          <span class="debug-value error text-red-500">{{ store.errorMessage }}</span>
        </div>
        <div class="debug-row">
          <span class="debug-label text-white">Processing:</span>
          <span class="debug-value text-yellow-500">{{ store.serverSideOnly ? 'Server-only' : 'TensorFlow.js' }}</span>
          <button @click="toggleProcessingMode" class="debug-btn small">
            {{ store.serverSideOnly ? 'Enable TensorFlow.js' : 'Switch to Server-only' }}
          </button>
        </div>
        <div class="debug-row">
          <span class="debug-label text-white">TF Error Count:</span>
          <span class="debug-value text-red-500">{{ store.tfErrorCount }} / {{ store.maxTfErrors }}</span>
        </div>
      </div>
      
      <div class="debug-section">
        <h4>Request Details</h4>
        <div class="debug-row">
          <span class="debug-label text-white">Last API URL:</span>
          <span class="debug-value text-white">{{ lastApiUrl }}</span>
        </div>
        <div class="debug-row">
          <span class="debug-label text-white">Last Status:</span>
          <span class="debug-value text-green-500">{{ lastApiStatus }}</span>
        </div>
        <pre class="debug-json text-orange-400">{{ JSON.stringify(lastApiResponse, null, 2) }}</pre>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useItemDetectiveStore } from '@/stores/itemDetective.store';
import { 
  Search, Upload, Sparkles, 
  Loader, MapPin, Calendar, 
  Clock, Circle, Monitor, Watch, 
  Briefcase, Shirt, ChevronDown, SearchX,
  AlertTriangle
} from 'lucide-vue-next';
import * as tf from '@tensorflow/tfjs';
import axios from 'axios';

const store = useItemDetectiveStore();
const fileInput = ref(null);
const imageFile = ref(null);
const imagePreview = ref(null);
const isDragging = ref(false);
const showUploader = ref(true);
const matchThreshold = ref(70);
const progressStage = computed(() => store.searchStatus);

// Debug related
const isDevMode = ref(process.env.NODE_ENV === 'development');
const debugVisible = ref(false);
const apiConnected = ref(false);
const lastApiUrl = ref('');
const lastApiStatus = ref('');
const lastApiResponse = ref(null);
const showDebugPanel = ref(false);

watch(() => store.searchStatus, (newStatus) => {
  showUploader.value = !['analyzing', 'searching', 'complete'].includes(newStatus);
});

const handleFileSelect = (event) => {
  const file = event.target.files[0];
  processFile(file);
};

const handleDrop = (event) => {
  isDragging.value = false;
  const file = event.dataTransfer.files[0];
  
  if (file && file.type.startsWith('image/')) {
    processFile(file);
  } else {
    // Toast notification would be used here
    console.error('Please upload an image file');
  }
};

const processFile = (file) => {
  if (file && file.type.startsWith('image/')) {
    imageFile.value = file;
    
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const serverSideOnlyUpload = async () => {
  if (!imageFile.value) return;
  
  try {
    showUploader.value = false;
    store.isProcessing = true;
    
    // Force server-side only mode
    store.setServerSideOnly(true);
    
    // Use the store's built-in server-side processing
    await store.serverSideProcessing(imageFile.value);
    
  } catch (error) {
    console.error('Error with server-side fallback:', error);
    
    let errorMessage = 'Error searching for similar items.';
    
    // Handle specific error cases
    if (error.response) {
      // The request was made and the server responded with a status code
      // that falls out of the range of 2xx
      console.error('Server error response:', error.response.data);
      
      if (error.response.status === 401) {
        errorMessage = 'Authentication required. Please log in and try again.';
      } else if (error.response.status === 422) {
        errorMessage = 'Invalid image format. Please try with a different image.';
      } else if (error.response.status >= 500) {
        errorMessage = 'Server error. Please try again later.';
      }
    } else if (error.request) {
      // The request was made but no response was received
      errorMessage = 'Server not responding. Please check your connection and try again.';
    }
    
    store.errorMessage = errorMessage;
    store.searchStatus = 'error';
    store.isProcessing = false;
  }
};

const testConnection = async () => {
  try {
    const response = await axios.get('/api/ping');
    apiConnected.value = true;
    lastApiUrl.value = '/api/ping';
    lastApiStatus.value = response.status;
    lastApiResponse.value = response.data;
    return true;
  } catch (error) {
    apiConnected.value = false;
    lastApiUrl.value = '/api/ping';
    lastApiStatus.value = error.response ? error.response.status : 'No response';
    lastApiResponse.value = error.response ? error.response.data : error.message;
    return false;
  }
};

const startDetection = async () => {
  if (!imageFile.value) return;
  
  // Reset any previous state and errors
  store.resetState();
  showUploader.value = false;
  
  try {
    // Test API connection first
    console.log('Testing API connection...');
    const connected = await testConnection();
    
    if (!connected) {
      throw new Error('API connection failed. Please check your backend server.');
    }
    
    // Use a more resilient approach
    console.log('Starting item detection process...');
    
    // Try to process the image with TensorFlow.js
    await store.processImage(imageFile.value);
    
    console.log('Image processing completed successfully');
  } catch (error) {
    console.error('Error during detection with TensorFlow.js, trying fallback:', error);
    
    try {
      // Try server-side only approach if TensorFlow fails
      await serverSideOnlyUpload();
    } catch (fallbackError) {
      console.error('Fallback also failed:', fallbackError);
      store.errorMessage = store.errorMessage || 'Could not connect to the server. Please check your internet connection and try again.';
      store.searchStatus = 'error';
      
      // Even if both methods fail, try to display the upload form again
      setTimeout(() => {
        showUploader.value = true;
      }, 2000);
    }
  }
};

const resetDetection = () => {
  store.resetState();
  imageFile.value = null;
  imagePreview.value = null;
  showUploader.value = true;
};

const getMatchBadgeClass = (percentage) => {
  if (percentage >= 90) return 'high-match';
  if (percentage >= 75) return 'medium-match';
  return 'low-match';
};

// Override axios to track API calls
const originalPost = axios.post;
axios.post = async function(url, data, config) {
  lastApiUrl.value = url;
  try {
    const response = await originalPost.call(this, url, data, config);
    lastApiStatus.value = response.status;
    lastApiResponse.value = response.data;
    return response;
  } catch (error) {
    lastApiStatus.value = error.response ? error.response.status : 'No response';
    lastApiResponse.value = error.response ? error.response.data : error.message;
    throw error;
  }
};

// Check API on mount
onMounted(async () => {
  try {
    // First check API connectivity
    await testConnection();
    
    console.log('Initializing TensorFlow.js...');
    await tf.ready();
    console.log('TensorFlow.js initialized with backend:', tf.getBackend());
    
    // Pre-warm the backend
    tf.tidy(() => {
      const dummy = tf.zeros([1, 1, 1, 1]);
      dummy.dispose();
    });
    
    // Try to pre-load the model but don't block the UI
    setTimeout(() => {
      store.loadModel().catch(error => {
        console.warn('Model preloading failed, will try again when needed:', error);
      });
    }, 1000);
  } catch (error) {
    console.error('Error initializing TensorFlow.js:', error);
  }
});

const toggleProcessingMode = () => {
  store.serverSideOnly = !store.serverSideOnly;
};
</script>

<style scoped>
.item-detective-container {
  min-height: 100vh;
  background-color: #0f172a;
  color: #e2e8f0;
}

.header-banner {
  background: linear-gradient(to right, #4f46e5, #7c3aed);
  padding: 1rem 2rem;
  position: sticky;
  top: 0;
  z-index: 10;
}

.search-bar {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.search-bar h1 {
  font-size: 1.5rem;
  font-weight: 600;
  margin: 0;
}

.subtitle {
  font-size: 0.875rem;
  opacity: 0.8;
  margin-left: auto;
}

.main-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
}

.upload-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 1.5rem;
  max-width: 500px;
  margin: 2rem auto;
}

.dropzone {
  width: 100%;
  aspect-ratio: 4/3;
  border: 2px dashed #4f46e5;
  border-radius: 0.75rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s;
  background-color: rgba(79, 70, 229, 0.05);
  overflow: hidden;
  position: relative;
}

.dropzone.active {
  border-color: #818cf8;
  background-color: rgba(79, 70, 229, 0.1);
}

.dropzone.has-preview {
  border-style: solid;
}

.upload-prompts {
  text-align: center;
}

.upload-icon {
  margin-bottom: 1rem;
  color: #818cf8;
}

.file-type-hint {
  display: block;
  font-size: 0.75rem;
  color: #94a3b8;
  margin-top: 0.5rem;
}

.hidden {
  display: none;
}

.image-preview {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
  border-radius: 0.5rem;
}

.detect-button {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  background-color: #4f46e5;
  color: white;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  font-weight: 600;
  transition: all 0.2s;
  border: none;
  cursor: pointer;
}

.detect-button:hover {
  background-color: #4338ca;
}

.detect-button:disabled {
  background-color: #6b7280;
  cursor: not-allowed;
}

/* Progress View */
.detection-progress {
  max-width: 600px;
  margin: 0 auto;
}

.card {
  background-color: #1e293b;
  border-radius: 1rem;
  padding: 2rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  text-align: center;
}

.item-card-image {
  width: 150px;
  height: 150px;
  object-fit: cover;
  border-radius: 0.5rem;
  margin: 0 auto 1.5rem;
  display: block;
}

.spinner {
  margin: 2rem auto;
  color: #818cf8;
}

.progress-container {
  margin: 2rem 0;
}

.progress-label {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.5rem;
  font-size: 0.875rem;
}

.progress-bar {
  height: 0.5rem;
  background-color: #334155;
  border-radius: 0.25rem;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  background: linear-gradient(to right, #4f46e5, #7c3aed);
  border-radius: 0.25rem;
  transition: width 0.3s ease;
}

.ai-detection-results {
  margin-top: 2rem;
  text-align: left;
  background-color: #182234;
  border-radius: 0.75rem;
  padding: 1.5rem;
}

.ai-detection-results h3 {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 1rem;
  margin-top: 0;
  color: #a5b4fc;
}

.detection-result-item {
  margin: 1rem 0;
}

.detection-result-item .label {
  font-size: 0.875rem;
  margin-bottom: 0.25rem;
}

.value-bar {
  height: 0.5rem;
  background-color: #334155;
  border-radius: 0.25rem;
  overflow: hidden;
  margin-bottom: 0.25rem;
}

.bar-fill {
  height: 100%;
  background: linear-gradient(to right, #4f46e5, #7c3aed);
  border-radius: 0.25rem;
}

.value {
  font-size: 0.875rem;
  font-weight: 500;
}

/* Results View */
.results-container {
  display: grid;
  grid-template-columns: 300px 1fr;
  gap: 2rem;
}

.source-image-container {
  background-color: #1e293b;
  border-radius: 0.75rem;
  overflow: hidden;
}

.source-image {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.ai-results {
  padding: 1rem;
}

.ai-results h4 {
  font-size: 0.875rem;
  margin: 0 0 1rem;
  color: #94a3b8;
}

.result-rows {
  margin-bottom: 1rem;
}

.result-row {
  display: grid;
  grid-template-columns: 80px 1fr auto;
  gap: 0.5rem;
  align-items: center;
  margin-bottom: 0.75rem;
}

.result-row .label {
  font-size: 0.75rem;
}

.result-row .value {
  font-size: 0.75rem;
  font-weight: 600;
}

.result-row .progress-bar {
  height: 0.375rem;
}

.search-again-btn {
  width: 100%;
  background-color: #334155;
  color: white;
  border: none;
  padding: 0.5rem;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  cursor: pointer;
  transition: all 0.2s;
}

.search-again-btn:hover {
  background-color: #475569;
}

.filter-section {
  padding: 1rem;
  border-top: 1px solid #334155;
}

.filter-section h4 {
  font-size: 0.875rem;
  margin: 0 0 1rem;
  color: #94a3b8;
}

.match-threshold {
  margin-bottom: 1.5rem;
}

.threshold-header {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.5rem;
  font-size: 0.75rem;
}

.threshold-slider {
  width: 100%;
  accent-color: #4f46e5;
}

.categories {
  margin-top: 1rem;
}

.category-header {
  font-size: 0.75rem;
  margin-bottom: 0.5rem;
}

.category-tags {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.category-tag {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem;
  font-size: 0.75rem;
  background-color: #334155;
  border-radius: 0.375rem;
  cursor: pointer;
}

.category-tag.active {
  background-color: #4f46e5;
}

.count {
  margin-left: auto;
  background-color: rgba(255, 255, 255, 0.1);
  padding: 0.125rem 0.375rem;
  border-radius: 0.25rem;
  font-size: 0.625rem;
}

.results-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 0.5rem;
}

.results-header h2 {
  font-size: 1.25rem;
  font-weight: 600;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.result-count-badge {
  background-color: #4f46e5;
  padding: 0.25rem 0.75rem;
  border-radius: 0.375rem;
  font-size: 0.75rem;
  font-weight: 500;
}

.results-description {
  color: #94a3b8;
  font-size: 0.875rem;
  margin-bottom: 1.5rem;
}

.results-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1rem;
}

.result-card {
  background-color: #1e293b;
  border-radius: 0.75rem;
  overflow: hidden;
  transition: transform 0.2s;
}

.result-card:hover {
  transform: translateY(-2px);
}

.card-image-container {
  position: relative;
}

.card-image {
  width: 100%;
  height: 180px;
  object-fit: cover;
}

.match-badge {
  position: absolute;
  top: 0.5rem;
  right: 0.5rem;
  background-color: #4f46e5;
  color: white;
  padding: 0.25rem 0.5rem;
  border-radius: 0.25rem;
  font-size: 0.75rem;
  font-weight: 600;
}

.match-badge.high-match {
  background-color: #10b981;
}

.match-badge.medium-match {
  background-color: #f59e0b;
}

.match-badge.low-match {
  background-color: #ef4444;
}

.card-content {
  padding: 1rem;
}

.item-name {
  font-size: 1rem;
  font-weight: 600;
  margin: 0 0 0.75rem;
}

.item-details {
  margin-bottom: 0.75rem;
}

.detail-row {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.75rem;
  color: #94a3b8;
  margin-bottom: 0.25rem;
}

.item-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-bottom: 1rem;
}

.tag {
  background-color: #334155;
  color: #e2e8f0;
  padding: 0.25rem 0.5rem;
  border-radius: 0.25rem;
  font-size: 0.75rem;
}

.card-actions {
  display: flex;
  gap: 0.5rem;
}

.contact-btn {
  flex: 1;
  background-color: #4f46e5;
  color: white;
  border: none;
  padding: 0.5rem;
  border-radius: 0.375rem;
  font-size: 0.75rem;
  cursor: pointer;
}

.more-btn {
  background-color: #334155;
  color: white;
  border: none;
  padding: 0.5rem;
  border-radius: 0.375rem;
  font-size: 0.75rem;
  display: flex;
  align-items: center;
  gap: 0.25rem;
  cursor: pointer;
}

.empty-results {
  grid-column: 1 / -1;
  text-align: center;
  padding: 3rem;
  background-color: #1e293b;
  border-radius: 0.75rem;
}

.empty-icon {
  margin-bottom: 1rem;
  color: #64748b;
}

.try-again-btn {
  background-color: #4f46e5;
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  margin-top: 1.5rem;
  cursor: pointer;
}

.error-container {
  text-align: center;
  max-width: 500px;
  margin: 3rem auto;
  padding: 2rem;
  background-color: #1e293b;
  border-radius: 0.75rem;
}

.error-icon {
  color: #ef4444;
  margin-bottom: 1rem;
}

@media (max-width: 768px) {
  .results-container {
    grid-template-columns: 1fr;
  }
  
  .source-image-container {
    margin-bottom: 1.5rem;
  }
}

/* Debug Panel Styles */
.debug-panel {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  background-color: #0f172a;
  border-top: 1px solid #334155;
  z-index: 1000;
  font-size: 0.75rem;
}

.debug-panel-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem 1rem;
  background-color: #1e293b;
}

.debug-panel-header h3 {
  margin: 0;
  font-size: 0.875rem;
}

.debug-panel-content {
  padding: 1rem;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
  max-height: 300px;
  overflow-y: auto;
}

.debug-section {
  background-color: #1e293b;
  padding: 0.75rem;
  border-radius: 0.25rem;
}

.debug-section h4 {
  margin: 0 0 0.5rem;
  font-size: 0.75rem;
  color: #94a3b8;
}

.debug-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.5rem;
}

.debug-label {
  font-size: 0.75rem;
}

.debug-value {
  font-size: 0.75rem;
  font-weight: 600;
}

.debug-value.connected {
  color: #10b981;
}

.debug-value.error {
  color: #ef4444;
}

.debug-btn {
  background-color: #334155;
  border: none;
  color: white;
  padding: 0.25rem 0.5rem;
  font-size: 0.625rem;
  border-radius: 0.25rem;
  margin-top: 0.5rem;
  cursor: pointer;
}

.debug-json {
  margin-top: 0.5rem;
  padding: 0.5rem;
  background-color: #0f172a;
  border-radius: 0.25rem;
  white-space: pre-wrap;
  font-family: monospace;
  font-size: 0.625rem;
  max-height: 100px;
  overflow-y: auto;
}

.status-ok {
  color: #10b981;
}

.status-error {
  color: #ef4444;
}

.status-warning {
  color: #f59e0b;
}

.status-idle {
  color: #94a3b8;
}

.status-loading, .status-analyzing, .status-searching {
  color: #f59e0b;
}

.status-complete {
  color: #10b981;
}

.processing-mode {
  margin-top: 0.5rem;
  font-size: 0.75rem;
}

.debug-btn.small {
  padding: 0.25rem 0.5rem;
  font-size: 0.625rem;
}
</style> 