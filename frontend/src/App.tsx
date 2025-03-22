import { useState, useEffect } from 'react'
import axios from 'axios'

export default function App() {
    const [data, setData] = useState(null)
    const [loading, setLoading] = useState(true)
    const [error, setError] = useState(null)

    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await axios.get('http://localhost:8000/api/test')
                setData(response.data)
            } catch (err) {
                // @ts-ignore
                setError(err.message)
            } finally {
                setLoading(false)
            }
        }

        fetchData()
    }, [])

    // @ts-ignore
    // @ts-ignore
    return (
        <div className="container">
            <h1>Laravel API Response</h1>

            {loading && <p>Loading...</p>}

            {error && (
                <div className="error">
                    Error: {error}
                </div>
            )}

            {data && (
                <div className="success">
                    <h2>{data.success}</h2>
                    <pre>{JSON.stringify(data, null, 2)}</pre>
                </div>
            )}
        </div>
    )
}