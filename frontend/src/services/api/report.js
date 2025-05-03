import { BaseService } from '../BaseService';

class ReportService extends BaseService {
  constructor() {
    super('/admin/reports');
  }

  /**
   * Get all reports with filtering
   * @param {Object} params - Filter parameters
   * @returns {Promise} - Reports response
   */
  async getReports(params = {}) {
    return this.get('', params);
  }

  /**
   * Get a single report
   * @param {string} id - Report ID
   * @returns {Promise} - Report response
   */
  async getReport(id) {
    return this.get(`/${id}`);
  }

  /**
   * Update report status
   * @param {string} id - Report ID
   * @param {string} status - New status
   * @param {string} resolution - Resolution details
   * @returns {Promise} - Update response
   */
  async updateReportStatus(id, status, resolution = null) {
    return this.patch(`/${id}/status`, { status, resolution });
  }

  /**
   * Dismiss report
   * @param {string} id - Report ID
   * @param {string} resolution - Dismissal reason
   * @returns {Promise} - Dismiss response
   */
  async dismissReport(id, resolution) {
    return this.patch(`/${id}/dismiss`, { resolution });
  }

  /**
   * Get report statistics
   * @returns {Promise} - Stats response
   */
  async getReportStats() {
    return this.get('/stats');
  }

  /**
   * Submit a report
   * @param {string} reportableType - Type of reportable item
   * @param {string} reportableId - ID of reportable item
   * @param {string} reason - Report reason
   * @param {string} details - Additional details
   * @returns {Promise} - Submit response
   */
  async submitReport(reportableType, reportableId, reason, details = null) {
    return this.post('', {
      reportable_type: reportableType,
      reportable_id: reportableId,
      reason,
      details
    });
  }
}

export default new ReportService();
