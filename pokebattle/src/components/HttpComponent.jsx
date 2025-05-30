import { useState } from 'react';
import '@/styles/http.css';

const HttpComponent = ({ title, jsonExample, onTryOut }) => {
  const [response, setResponse] = useState(null);

  const handleClick = async () => {
    try {
      const res = await onTryOut();
      setResponse({ data: res?.data, error: false });
    } catch (e) {
      const errorMsg = e.response?.data ?? { error: 'Unknown error'};
      setResponse({ data: errorMsg, error: true });
    }
  };

  return (
    <div className="container mt-4">
      <h2>{title}</h2>

      <h5>Request Example:</h5>
      <pre className="bg-light p-3 rounded">
        {JSON.stringify(jsonExample, null, 2)}
      </pre>

      <button className="btn btn-primary mb-3" onClick={handleClick}>Try Out</button>

      {response && (
        <>
          <h5>Response:</h5>
          <pre className={`p-3 rounded ${response.error ? 'bg-danger-subtle' : 'bg-success-subtle'}`}>
            {JSON.stringify(response.data, null, 2)}
          </pre>
        </>
      )}
    </div>
  );
};

export default HttpComponent;
