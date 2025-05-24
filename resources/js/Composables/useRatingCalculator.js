export function useRatingCalculator() {

    /**
     * Returns the average rating of a tasca
     * @param {Object} tasca
     * @returns {number} media (decimal)
     */
    function getAverageRating(tasca) {
        const reviews = tasca.reviews || []
        if (reviews.length === 0) return 0
        const sum = reviews.reduce((total, r) => total + r.rating, 0)
        return sum / reviews.length
    }

    /**
     * Returns the rounded rating of a tasca
     * @param {Object} tasca
     * @returns {number} entero entre 0 y 5
     */
    function getRoundedRating(tasca) {
        return Math.round(getAverageRating(tasca))
    }

    return {
        getRoundedRating
    }
}
