/**
 * Class that represents any errors within a form.
 * Constructor will be called from the Form class
 */
export default class Errors {
    /**
     * Creates instance of Errors with empty object
     */
    constructor() {
        this.errors = {}
    }

     /**
     * Check if there are any errors
     * @returns {boolean} Of errors property exists with the name of the field
     */
    any() {
        return Object.keys(this.errors).length > 0
    }

    /**
     * Check errors property exists with the name of the field
     * @param {string} field to check for errors
     * @returns {boolean} If errors property exists with the name of the field
     */
    has(field) {
        return this.errors.hasOwnProperty(field)
    }
    
    /**
     * Get error for a field. First in array
     * @param {string} field to get error for
     * @returns {string} Error for the field
     */
    get(field) {
        if(this.errors[field]) {
            return this.errors[field][0]
        }
    }

    /**
     * Get one error for a field. (required if error does not return an array)
     * @param {string} field to get error for
     * @returns {string} Error for the field
     */
    getOne(field) {
        if(this.errors[field]) {
            return this.errors[field]
        }
    }

    /**
     * Save errors into the class
     * @param {object} errors returned from form submission
     */
    record(errors) {
        this.errors = errors
    }

    /**
     * Clear error for a field or all if no param sent
     * @param {String} field to clear error
     */
    clear(field) {
        if(field) {
            delete this.errors[field]
        }
        else {
            this.errors = {}
        }
    }
}