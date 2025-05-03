import { BaseService } from '../BaseService';

export class MatchService extends BaseService {
  constructor() {
    super('/matches');
  }

  /**
   * Get all matches
   * @param {Object} params - Query parameters for filtering matches
   * @returns {Promise} - Matches response
   */
  async getMatches(params = {}) {
    return this.get('', params);
  }

  /**
   * Get matches for a specific item
   * @param {string} itemId - Item ID
   * @returns {Promise} - Matches response
   */
  async getMatchesForItem(itemId) {
    return this.get(`/item/${itemId}`);
  }

  /**
   * Mark match as resolved
   * @param {string} matchId - Match ID
   * @returns {Promise} - Resolve response
   */
  async resolveMatch(matchId) {
    return this.patch(`/${matchId}/resolve`);
  }

  /**
   * Get match statistics
   * @returns {Promise} - Stats response
   */
  async getMatchStats() {
    return this.get('/stats');
  }

  /**
   * Update match status
   * @param {string} matchId - Match ID
   * @param {string} status - New status
   * @returns {Promise} - Update response
   */
  async updateStatus(matchId, status) {
    return this.patch(`/${matchId}/status`, { status });
  }
}

// Export specific functions
export const getMatches = (params) => new MatchService().getMatches(params);
export const getMatchesForItem = (itemId) => new MatchService().getMatchesForItem(itemId);
export const resolveMatch = (matchId) => new MatchService().resolveMatch(matchId);
export const getMatchStats = () => new MatchService().getMatchStats();
export const updateStatus = (matchId, status) => new MatchService().updateStatus(matchId, status);
