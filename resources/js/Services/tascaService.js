import axiosInstance from "@/Services/axios.js";

export default  {

    /**
     * Fetch all tascas from the API. If no page is provided, it defaults to 1.
     * @param page Page number to fetch
     * @returns {Promise<void>}
     */
    async getAll(page = 1) {
        try {
            const response = await axiosInstance
                .get(`/public/tascas?page=${page}`);
            return response.data;
        } catch (err) {
            throw new Error(err.response.data.message);
        }
    },

    /**
     * Fetch a single tasca by ID from the API
     * @param id Tasca's ID
     * @returns {Promise<void>}
     */
    async getById(id) {
        try {
            const response = await axiosInstance
                .get(`/api/public/tascas/${id}`);
            return response.data;
        } catch (err) {
            throw new Error(err.response.data.message);
        }
    },

    /**
     * Delete a tasca
     * @param id Tasca's ID
     * @returns {Promise<void>}
     */
    async deleteById(id) {
        try {
            await axiosInstance.delete(`/api/public/tascas/${id}`);
            tascas.value = tascas.value.filter(tasca => tasca.id !== id);
        } catch (err) {
            throw new Error(err.response.data.message);
        }
    },

    /**
     * Update a tasca
     * @param id Tasca's ID
     * @param data Data to update
     * @returns {Promise<void>}
     */
    async updateById(id, data) {
        try {
            const response = await axiosInstance
                .put(`/api/tasca/tascas/${id}`, data);
            tasca.value = response.data;
        } catch (err) {
            throw new Error(err.response.data.message);
        }
    }
}
