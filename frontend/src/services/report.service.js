import axios from 'axios';
import authHeader from './auth-header';

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';

class ReportService {
  // Get all reports with filtering
  async getReports(params = {}) {
    try {
      const response = await axios.get(`${API_URL}/admin/reports`, { 
        headers: authHeader(),
        params
      });
      return response;
    } catch (error) {
      console.error('Error in getReports:', error);
      // Return a structured response even in error cases
      return { data: { data: [] } };
    }
  }

  // Get a specific report
  async getReport(id) {
    try {
      const response = await axios.get(`${API_URL}/admin/reports/${id}`, { 
        headers: authHeader()
      });
      return response;
    } catch (error) {
      console.error(`Error fetching report details for ID ${id}:`, error);
      throw error;
    }
  }

  // Update a report's status
  async updateReportStatus(id, status, resolution = null) {
    try {
      const response = await axios.patch(
        `${API_URL}/admin/reports/${id}/status`, 
        { status, resolution }, 
        { headers: authHeader() }
      );
      return response;
    } catch (error) {
      console.error(`Error updating report status for ID ${id}:`, error);
      throw error;
    }
  }

  // Resolve a report
  async resolveReport(id, resolution = null) {
    try {
      const response = await axios.patch(
        `${API_URL}/admin/reports/${id}/resolve`, 
        { resolution }, 
        { headers: authHeader() }
      );
      return response;
    } catch (error) {
      console.error(`Error resolving report ID ${id}:`, error);
      throw error;
    }
  }

  // Dismiss a report
  async dismissReport(id, resolution = null) {
    try {
      const response = await axios.patch(
        `${API_URL}/admin/reports/${id}/dismiss`, 
        { resolution }, 
        { headers: authHeader() }
      );
      return response;
    } catch (error) {
      console.error(`Error dismissing report ID ${id}:`, error);
      throw error;
    }
  }

  // Get report statistics
  async getReportStats() {
    try {
      const response = await axios.get(`${API_URL}/admin/reports/stats`, { 
        headers: authHeader()
      });
      return response;
    } catch (error) {
      console.error('Error fetching report statistics:', error);
      throw error;
    }
  }
  
  // Additional actions for reported items

  // Ban a user
  async banUser(userId, reason = null) {
    try {
      const response = await axios.patch(
        `${API_URL}/admin/users/${userId}/ban`, 
        { reason }, 
        { headers: authHeader() }
      );
      return response;
    } catch (error) {
      console.error(`Error banning user ID ${userId}:`, error);
      throw error;
    }
  }

  // Suspend a user
  async suspendUser(userId, days, reason = null) {
    try {
      const response = await axios.patch(
        `${API_URL}/admin/users/${userId}/suspend`, 
        { days, reason }, 
        { headers: authHeader() }
      );
      return response;
    } catch (error) {
      console.error(`Error suspending user ID ${userId}:`, error);
      throw error;
    }
  }

  // Delete an item
  async deleteItem(itemId, reason = null) {
    try {
      const response = await axios.delete(
        `${API_URL}/admin/items/${itemId}`, 
        { 
          headers: authHeader(),
          data: { reason }
        }
      );
      return response;
    } catch (error) {
      console.error(`Error deleting item ID ${itemId}:`, error);
      throw error;
    }
  }
}

export default new ReportService();
