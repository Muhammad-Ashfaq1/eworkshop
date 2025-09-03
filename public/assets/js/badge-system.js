/**
 * Unified Badge System for eworkshop
 * Provides consistent status badges across all tables
 */

/**
 * Generate a status badge for is_active field
 * @param {boolean|number} isActive - The active status (1/0 or true/false)
 * @param {string} customActiveText - Custom text for active state (default: 'Active')
 * @param {string} customInactiveText - Custom text for inactive state (default: 'Inactive')
 * @returns {string} HTML string for the badge
 */
function createStatusBadge(isActive, customActiveText = 'Active', customInactiveText = 'Inactive') {
    const active = isActive == 1 || isActive === true;
    return active ? 
        `<span class="badge bg-success-subtle text-success"><i class="ri-check-line me-1"></i>${customActiveText}</span>` :
        `<span class="badge bg-danger-subtle text-danger"><i class="ri-close-line me-1"></i>${customInactiveText}</span>`;
}

/**
 * Generate a condition badge (for vehicles, equipment, etc.)
 * @param {string} condition - The condition value ('new', 'old', 'good', 'fair', 'poor', etc.)
 * @returns {string} HTML string for the badge
 */
function createConditionBadge(condition) {
    if (!condition) return '<span class="badge bg-light">N/A</span>';
    
    const conditionMap = {
        'new': { class: 'bg-success vehicle-condition-badge', icon: 'ri-star-line' },
        'good': { class: 'bg-success vehicle-condition-badge', icon: 'ri-thumb-up-line' },
        'fair': { class: 'bg-warning vehicle-condition-badge', icon: 'ri-alert-line' },
        'poor': { class: 'bg-danger vehicle-condition-badge', icon: 'ri-thumb-down-line' },
        'old': { class: 'bg-warning vehicle-condition-badge', icon: 'ri-time-line' },
        'excellent': { class: 'bg-primary vehicle-condition-badge', icon: 'ri-medal-line' }
    };
    
    const config = conditionMap[condition.toLowerCase()] || { class: 'bg-secondary vehicle-condition-badge', icon: 'ri-question-line' };
    const displayText = condition.charAt(0).toUpperCase() + condition.slice(1);
    
    return `<span class="badge ${config.class}"><i class="${config.icon} me-1"></i>${displayText}</span>`;
}

/**
 * Generate a type badge (for reports, documents, etc.)
 * @param {string} type - The type value
 * @param {object} customMap - Custom type to badge mapping
 * @returns {string} HTML string for the badge
 */
function createTypeBadge(type, customMap = {}) {
    if (!type) return '<span class="badge bg-light">N/A</span>';
    
    const defaultTypeMap = {
        'defect_report': { class: 'bg-warning-subtle text-warning type-badge', icon: 'ri-tools-line' },
        'purchase_order': { class: 'bg-info-subtle text-info type-badge', icon: 'ri-shopping-cart-line' },
        'maintenance': { class: 'bg-primary-subtle text-primary type-badge', icon: 'ri-settings-line' },
        'inspection': { class: 'bg-success-subtle text-success type-badge', icon: 'ri-search-line' },
        'fleet_manager': { class: 'bg-primary-subtle text-primary type-badge', icon: 'ri-user-settings-line' },
        'mvi': { class: 'bg-info-subtle text-info type-badge', icon: 'ri-user-star-line' }
    };
    
    const typeMap = { ...defaultTypeMap, ...customMap };
    const config = typeMap[type.toLowerCase()] || { class: 'bg-secondary-subtle text-secondary type-badge', icon: 'ri-bookmark-line' };
    
    const displayText = type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    
    return `<span class="badge ${config.class}"><i class="${config.icon} me-1"></i>${displayText}</span>`;
}

/**
 * Generate a count badge (for works, parts, etc.)
 * @param {number|array} count - The count value or array to count
 * @param {string} label - Label for the count (default: empty)
 * @returns {string} HTML string for the badge
 */
function createCountBadge(count, label = '') {
    const actualCount = Array.isArray(count) ? count.length : (count || 0);
    const badgeClass = actualCount > 0 ? 'count-badge' : 'count-badge empty';
    const icon = actualCount > 0 ? 'ri-hash' : 'ri-subtract-line';
    
    return `<span class="badge ${badgeClass}"><i class="${icon} me-1"></i>${actualCount}${label ? ' ' + label : ''}</span>`;
}

/**
 * Generate a priority badge
 * @param {string} priority - Priority level ('high', 'medium', 'low', 'urgent')
 * @returns {string} HTML string for the badge
 */
function createPriorityBadge(priority) {
    if (!priority) return '<span class="badge bg-light text-muted">N/A</span>';
    
    const priorityMap = {
        'urgent': { class: 'bg-danger', icon: 'ri-alarm-warning-line' },
        'high': { class: 'bg-warning', icon: 'ri-arrow-up-line' },
        'medium': { class: 'bg-info', icon: 'ri-arrow-right-line' },
        'low': { class: 'bg-success', icon: 'ri-arrow-down-line' }
    };
    
    const config = priorityMap[priority.toLowerCase()] || { class: 'bg-secondary', icon: 'ri-equal-line' };
    const displayText = priority.charAt(0).toUpperCase() + priority.slice(1);
    
    return `<span class="badge ${config.class}"><i class="${config.icon} me-1"></i>${displayText}</span>`;
}

/**
 * Generate a role badge
 * @param {string} role - Role name
 * @returns {string} HTML string for the badge
 */
function createRoleBadge(role) {
    if (!role) return '<span class="badge bg-light text-muted">No Role</span>';
    
    const roleMap = {
        'super_admin': { class: 'bg-danger-subtle text-danger', icon: 'ri-admin-line' },
        'admin': { class: 'bg-primary-subtle text-primary', icon: 'ri-user-settings-line' },
        'deo': { class: 'bg-info-subtle text-info', icon: 'ri-user-line' },
        'fleet_manager': { class: 'bg-warning-subtle text-warning', icon: 'ri-truck-line' },
        'mvi': { class: 'bg-success-subtle text-success', icon: 'ri-user-star-line' }
    };
    
    const config = roleMap[role.toLowerCase()] || { class: 'bg-secondary-subtle text-secondary', icon: 'ri-user-line' };
    const displayText = role.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    
    return `<span class="badge ${config.class}"><i class="${config.icon} me-1"></i>${displayText}</span>`;
}

/**
 * Generate attachment status badge
 * @param {string|object} attachment - Attachment data or URL
 * @returns {string} HTML string for the badge
 */
function createAttachmentBadge(attachment) {
    if (!attachment) {
        return '<span class="badge bg-light text-muted"><i class="ri-attachment-line me-1"></i>No File</span>';
    }
    
    return '<button class="btn btn-sm btn-outline-success"><i class="ri-eye-line me-1"></i>View</button>';
}

/**
 * Enhanced DataTable column renderer for relationship fields with sorting
 * @param {string} relationField - The relation field name (e.g., 'location.name')
 * @param {string} fallback - Fallback text when no data
 * @returns {object} DataTable column configuration
 */
function createRelationColumn(relationField, fallback = 'N/A') {
    return {
        render: function(data, type, row) {
            // Support nested relations (e.g., 'defectReport.vehicle.vehicle_number')
            const fieldParts = relationField.split('.');
            let value = row;
            
            for (const part of fieldParts) {
                value = value && value[part];
                if (!value) break;
            }
            
            return value || fallback;
        },
        // Enable sorting on the relation field
        orderable: true,
        searchable: true
    };
}

/**
 * Create standardized date column
 * @param {string} format - Moment.js format string (default: 'MMM DD, YYYY')
 * @returns {object} DataTable column configuration
 */
function createDateColumn(format = 'MMM DD, YYYY') {
    return {
        render: function(data, type, row) {
            if (!data) return 'N/A';
            
            if (type === 'sort' || type === 'type') {
                return data; // Return raw data for sorting
            }
            
            return moment(data).format(format);
        },
        orderable: true,
        searchable: false
    };
}

/**
 * Create standardized currency column
 * @param {string} currency - Currency symbol (default: '$')
 * @param {number} decimals - Number of decimal places (default: 2)
 * @returns {object} DataTable column configuration
 */
function createCurrencyColumn(currency = '$', decimals = 2) {
    return {
        render: function(data, type, row) {
            if (!data || data === 0) return 'N/A';
            
            if (type === 'sort' || type === 'type') {
                return parseFloat(data); // Return number for sorting
            }
            
            return currency + parseFloat(data).toFixed(decimals);
        },
        orderable: true,
        searchable: false,
        className: 'text-end'
    };
} 