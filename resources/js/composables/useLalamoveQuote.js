import { ref, computed } from 'vue'
import { fetchQuote } from './lalamoveService'

export function useLalamoveQuote(){
    const loading = ref(false)
    const error = ref(null)

    const deliveryFee = ref('NT$ 0')
    const deliveryFee2 = ref('')
    const deliveryDistance = ref('--')
    const deliveryDistance2 = ref('')

    const quotationId = ref(null)
    const senderStopId = ref(null)
    const recipientStopId = ref(null)

    const totalAmount = ref('--')
    const total = ref('')
    const deliveryFeeValue = ref('')

    const total = computed(() => fee.value)

    async function getQuote({ pickup, destination, pickupAddress, destAddress }){
        loading.value = true
        error.value = null

        try
        {
            const controller = new AbortController();
            const timeout = setTimeout(() => controller.abort(), 5000); // 5s timeout

            const data = await fetchQuote({ 
                pickup,
                destination,
                pickupAddress,
                destAddress
            }, controller.signal)

            clearTimeout(timeout)

            deliveryFee.value = data.fee ? `NT$ ${data.fee}` : `NT$ 0`;
            deliveryFee2.value = data.fee || 0;
            deliveryDistance.value = data.distance_m ? `${(data.distance_m / 1000).toFixed(1)} km` : '--';
            deliveryDistance2.value = data.distance_m ? Number((data.distance_m / 1000).toFixed(1)) : 0;

            quotationId.value = data.quotation_id || '--';
            senderStopId.value = data.sender_stop_id || '--';
            recipientStopId.value = data.recipients_stop_id || '--';

            deliveryFeeValue.value = parseFloat(data.fee) || 0;
            total.value = totalFee.value.toFixed(2);
            totalAmount.value = `NT$ ${totalFee.value.toFixed(2)}`;
            
        }
        catch(error)
        {

        }
        finally
        {

        }
    }
}