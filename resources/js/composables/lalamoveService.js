import axios from 'axios'

export async function fetchQuote(payload, signal){
    
    const res = await axios.post('/api/lalamove/estimate', payload, {
        
        headers: {'Content-Type': 'application/json'},
        signal
    })

    return res.data

}