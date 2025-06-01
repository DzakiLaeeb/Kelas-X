import { useState } from 'react';
import { authService } from '../services/api';

const TestLogin = () => {
  const [result, setResult] = useState('');
  const [loading, setLoading] = useState(false);

  const testLogin = async () => {
    setLoading(true);
    setResult('Testing login...');
    
    try {
      console.log('🧪 Starting login test...');
      
      const credentials = {
        username: 'admin',
        password: 'admin123'
      };
      
      console.log('🧪 Test credentials:', credentials);
      
      const response = await authService.login(credentials);
      
      console.log('🧪 Login test response:', response);
      
      setResult(JSON.stringify(response, null, 2));
      
    } catch (error) {
      console.error('🧪 Login test error:', error);
      setResult(`Error: ${error.message}`);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div style={{ padding: '20px', maxWidth: '800px', margin: '0 auto' }}>
      <h1>Test Login API</h1>
      
      <button 
        onClick={testLogin} 
        disabled={loading}
        style={{
          padding: '10px 20px',
          backgroundColor: '#007bff',
          color: 'white',
          border: 'none',
          borderRadius: '5px',
          cursor: loading ? 'not-allowed' : 'pointer'
        }}
      >
        {loading ? 'Testing...' : 'Test Login (admin/admin123)'}
      </button>
      
      <div style={{ marginTop: '20px' }}>
        <h3>Result:</h3>
        <pre style={{
          backgroundColor: '#f8f9fa',
          padding: '15px',
          borderRadius: '5px',
          border: '1px solid #dee2e6',
          overflow: 'auto',
          fontSize: '12px'
        }}>
          {result || 'Click the button to test login'}
        </pre>
      </div>
      
      <div style={{ marginTop: '20px' }}>
        <h3>Instructions:</h3>
        <ol>
          <li>Open browser developer console (F12)</li>
          <li>Click the "Test Login" button above</li>
          <li>Check the console for detailed logs</li>
          <li>Check the result above for the API response</li>
        </ol>
      </div>
    </div>
  );
};

export default TestLogin;
