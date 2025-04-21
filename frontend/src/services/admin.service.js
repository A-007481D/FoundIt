import axios from 'axios';
import authHeader from './auth-header';

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';

class AdminService {
  // Dashboard
  getDashboardStats() {
    return axios.get(`${API_URL}/admin/dashboard`, { headers: authHeader() });
  }

  // Users
  getUsers(params = {}) {
    return axios.get(`${API_URL}/admin/users`, { 
      headers: authHeader(),
      params
    });
  }

  getUser(id) {
    return axios.get(`${API_URL}/admin/users/${id}`, { headers: authHeader() });
  }

  updateUserStatus(id, status) {
    return axios.patch(`${API_URL}/admin/users/${id}/status`, { status }, { headers: authHeader() });
  }

  updateUserRole(id, role) {
    return axios.patch(`${API_URL}/admin/users/${id}/role`, { role }, { headers: authHeader() });
  }

  getUserStats() {
    return axios.get(`${API_URL}/admin/users/stats`, { headers: authHeader() });
  }

  // Items
  getItems(params = {}) {
    return axios.get(`${API_URL}/admin/items`, { 
      headers: authHeader(),
      params
    });
  }

  getItem(id) {
    return axios.get(`${API_URL}/admin/items/${id}`, { headers: authHeader() });
  }

  updateItemStatus(id, status) {
    return axios.patch(`${API_URL}/admin/items/${id}/status`, { status }, { headers: authHeader() });
  }

  archiveItem(id) {
    return axios.patch(`${API_URL}/admin/items/${id}/archive`, {}, { headers: authHeader() });
  }

  deleteItem(id) {
    return axios.delete(`${API_URL}/admin/items/${id}`, { headers: authHeader() });
  }

  getItemStats() {
    return axios.get(`${API_URL}/admin/items/stats`, { headers: authHeader() });
  }

  // Reports
  getReports(params = {}) {
    return axios.get(`${API_URL}/admin/reports`, { 
      headers: authHeader(),
      params
    });
  }

  getReport(id) {
    return axios.get(`${API_URL}/admin/reports/${id}`, { headers: authHeader() });
  }

  updateReportStatus(id, status, resolution = null) {
    return axios.patch(
      `${API_URL}/admin/reports/${id}/status`, 
      { status, resolution }, 
      { headers: authHeader() }
    );
  }

  resolveReport(id, resolution = null) {
    return axios.patch(
      `${API_URL}/admin/reports/${id}/resolve`, 
      { resolution }, 
      { headers: authHeader() }
    );
  }

  dismissReport(id, resolution = null) {
    return axios.patch(
      `${API_URL}/admin/reports/${id}/dismiss`, 
      { resolution }, 
      { headers: authHeader() }
    );
  }

  getReportStats() {
    return axios.get(`${API_URL}/admin/reports/stats`, { headers: authHeader() });
  }

  // For regular users to submit reports
  submitReport(reportableType, reportableId, reason, details = null) {
    return axios.post(
      `${API_URL}/reports`, 
      { 
        reportable_type: reportableType, 
        reportable_id: reportableId,
        reason,
        details
      }, 
      { headers: authHeader() }
    );
  }
}

export default new AdminService();
