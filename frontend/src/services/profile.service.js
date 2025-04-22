import axios from 'axios';
import authHeader from './auth-header';

const API_URL = 'http://localhost:8000/api';

class ProfileService {
  // Get user profile
  getProfile() {
    return axios.get(API_URL + '/profile', { headers: authHeader() });
  }

  // Update user profile
  updateProfile(profileData) {
    return axios.put(API_URL + '/profile', profileData, { headers: authHeader() });
  }

  // Update profile photo
  updateProfilePhoto(photoFile) {
    const formData = new FormData();
    formData.append('photo', photoFile);
    
    return axios.post(API_URL + '/profile/photo', formData, {
      headers: {
        ...authHeader(),
        'Content-Type': 'multipart/form-data'
      }
    });
  }

  // Update notification preferences
  updateNotificationPreferences(preferences) {
    return axios.put(API_URL + '/profile/notifications', preferences, { headers: authHeader() });
  }

  // Update privacy settings
  updatePrivacySettings(settings) {
    return axios.put(API_URL + '/profile/privacy', settings, { headers: authHeader() });
  }
}

export default new ProfileService();
