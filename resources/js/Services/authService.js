import axiosInstance from "@/Services/axios.js";

export default {
    /**
     * Register a new customer
     * @returns {Promise<void>}
     */
    async createCustomer(customerData) {
        try {
            const response = await axiosInstance
                .post('/public/register/customer', customerData);
            return response.data;
        } catch (err) {
            throw new Error(err.response.data.message);
        }
    },

    /**
     * Register a new tasca
     * @returns {Promise<void>}
     */
    async registerTasca(tascaData) {
        try {
            const response = await axiosInstance
                .post('/public/register/tasca', tascaData);
            return response.data;
        } catch (err) {
            throw new Error(err.response.data.message);
        }
    },

    /**
     * Login a user
     * @returns {Promise<void>}
     */
    async login(userData) {
        try {
            const response = await axiosInstance.post('/api/public/login', userData);
            return response.data;
            //token.value = response.data.token;
        } catch (err) {
            error.value = err.response.data.message;
        }
    }
}
