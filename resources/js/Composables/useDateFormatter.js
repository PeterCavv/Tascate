export function useDateFormatter(){

    /**
     * Formats a date to DD-MM-YYYY
     * @param {string} date - The date to format
     * @returns {string} - The formatted date
     */
    function formateDateToDDMMYYYY(date){
        const fullDate = new Date(date);
        const day = String(fullDate.getUTCDate()).padStart(2, '0');
        const month = String(fullDate.getUTCMonth() + 1).padStart(2, '0');
        const year = fullDate.getUTCFullYear();
        return `${day}-${month}-${year}`;
    }

    /**
     * Checks if the given date is today
      * @param dateString
     * @returns {boolean}
     */
    function isToday(dateString) {
        const date = new Date(dateString);
        const today = new Date();

        return (
            date.getUTCFullYear() === today.getUTCFullYear() &&
            date.getUTCMonth() === today.getUTCMonth() &&
            date.getUTCDate() === today.getUTCDate()
        );
    }

    /**
     * Checks if the given date is before today
     * @param dateString
     * @returns {boolean}
     */
    function isBeforeToday(dateString) {
        const date = new Date(dateString);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        date.setHours(0, 0, 0, 0);
        return date < today;
    }

    /**
     * Checks if the given date is after today
     * @param dateString
     * @returns {boolean}
     */
    function isAfterToday(dateString) {
        const date = new Date(dateString);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        date.setHours(0, 0, 0, 0);
        return date > today;
    }

    return {
        formateDateToDDMMYYYY,
        isToday,
        isBeforeToday,
        isAfterToday
    };
}
