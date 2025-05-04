import {ref} from 'vue';
import axios from "axios";

export function usePublicTasca() {
    const tascas = ref([]);
    const tasca = ref({});
    const error = ref(null);

    /**
     * Fetch all tascas from the API
     * @returns {Promise<void>}
     */
    const getTascas = async () => {
        try {
            const response = await axios.get('/api/public/tascas');
            tascas.value = response.data;
            console.log(tascas.value);
        } catch (err) {
            error.value = err.response.data.message;
        }
    };

    /**
     * Fetch a single tasca by ID from the API
     * @param id
     * @returns {Promise<void>}
     */
    const getTasca = async (id) => {
        try {
            const response = await axios.get(`/api/public/tascas/${id}`);
            tasca.value = response.data;
        } catch (err) {
            error.value = err.response.data.message;
        }
    };

    /**
     * Delete a tasca
     * @param id
     * @returns {Promise<void>}
     */
    const deleteTasca = async (id) => {
        try {
            await axios.delete(`/api/public/tascas/${id}`);
            tascas.value = tascas.value.filter(tasca => tasca.id !== id);
        } catch (err) {
            error.value = err.response.data.message;
        }
    }

    return {
        tascas,
        tasca,
        error,
        getTascas,
        getTasca
    };
}
