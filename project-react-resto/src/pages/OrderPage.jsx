import React, { useState, useEffect } from 'react';

const OrderPage = () => {
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    // Simulate loading data
    setTimeout(() => {
      setOrders([
        { id: 1, customer: 'John Doe', total: 50000, status: 'Pending', date: '2024-01-15' },
        { id: 2, customer: 'Jane Smith', total: 75000, status: 'Completed', date: '2024-01-14' },
        { id: 3, customer: 'Bob Johnson', total: 30000, status: 'Processing', date: '2024-01-13' }
      ]);
      setLoading(false);
    }, 1000);
  }, []);

  const getStatusColor = (status) => {
    switch (status) {
      case 'Pending': return '#ffc107';
      case 'Processing': return '#FF6B35';
      case 'Completed': return '#28a745';
      default: return '#6c757d';
    }
  };

  return (
    <div style={{ backgroundColor: '#f8f9fa', minHeight: '100vh', padding: '20px' }}>
      <div style={{
        backgroundColor: 'white',
        borderRadius: '8px',
        padding: '30px',
        boxShadow: '0 2px 10px rgba(0,0,0,0.1)'
      }}>
        <div style={{ marginBottom: '30px' }}>
          <h2 style={{ margin: 0, fontWeight: '300', color: '#FF6B35', marginBottom: '10px' }}>
            Pesanan RestoKalel
          </h2>
          <p style={{ color: '#666', marginBottom: '20px' }}>
            Kelola pesanan dari pelanggan setia RestoKalel di sini.
          </p>
          <button style={{
            padding: '10px 20px',
            backgroundColor: '#FF6B35',
            color: 'white',
            border: 'none',
            borderRadius: '6px',
            fontSize: '14px',
            cursor: 'pointer'
          }}>
            Tambah Order Baru
          </button>
        </div>

        {loading ? (
          <div style={{
            textAlign: 'center',
            padding: '40px',
            fontSize: '16px',
            color: '#666'
          }}>
            Memuat data...
          </div>
        ) : error ? (
          <div style={{
            backgroundColor: '#f8d7da',
            color: '#721c24',
            padding: '15px',
            borderRadius: '6px',
            border: '1px solid #f5c6cb'
          }}>
            {error}
          </div>
        ) : (
          <div style={{ marginTop: '20px' }}>
            {orders.length === 0 ? (
              <div style={{
                textAlign: 'center',
                padding: '40px',
                color: '#6c757d',
                fontStyle: 'italic'
              }}>
                Tidak ada data order.
              </div>
            ) : (
              <div style={{ overflowX: 'auto' }}>
                <table style={{
                  width: '100%',
                  borderCollapse: 'collapse',
                  backgroundColor: 'white'
                }}>
                  <thead>
                    <tr style={{ backgroundColor: '#f8f9fa' }}>
                      <th style={{
                        padding: '15px',
                        textAlign: 'left',
                        borderBottom: '2px solid #dee2e6',
                        fontWeight: '500',
                        color: '#495057'
                      }}>
                        ID
                      </th>
                      <th style={{
                        padding: '15px',
                        textAlign: 'left',
                        borderBottom: '2px solid #dee2e6',
                        fontWeight: '500',
                        color: '#495057'
                      }}>
                        Customer
                      </th>
                      <th style={{
                        padding: '15px',
                        textAlign: 'left',
                        borderBottom: '2px solid #dee2e6',
                        fontWeight: '500',
                        color: '#495057'
                      }}>
                        Total
                      </th>
                      <th style={{
                        padding: '15px',
                        textAlign: 'left',
                        borderBottom: '2px solid #dee2e6',
                        fontWeight: '500',
                        color: '#495057'
                      }}>
                        Status
                      </th>
                      <th style={{
                        padding: '15px',
                        textAlign: 'left',
                        borderBottom: '2px solid #dee2e6',
                        fontWeight: '500',
                        color: '#495057'
                      }}>
                        Tanggal
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    {orders.map((order) => (
                      <tr key={order.id} style={{
                        borderBottom: '1px solid #dee2e6',
                        transition: 'background-color 0.2s ease'
                      }}
                      onMouseEnter={(e) => e.target.parentNode.style.backgroundColor = '#f8f9fa'}
                      onMouseLeave={(e) => e.target.parentNode.style.backgroundColor = 'transparent'}
                      >
                        <td style={{ padding: '15px', color: '#495057', fontWeight: '500' }}>
                          #{order.id}
                        </td>
                        <td style={{ padding: '15px', color: '#495057' }}>
                          {order.customer}
                        </td>
                        <td style={{ padding: '15px', color: '#6c757d' }}>
                          Rp {order.total.toLocaleString('id-ID')}
                        </td>
                        <td style={{ padding: '15px' }}>
                          <span style={{
                            padding: '4px 12px',
                            borderRadius: '15px',
                            fontSize: '12px',
                            fontWeight: '500',
                            backgroundColor: getStatusColor(order.status),
                            color: 'white'
                          }}>
                            {order.status}
                          </span>
                        </td>
                        <td style={{ padding: '15px', color: '#6c757d' }}>
                          {order.date}
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            )}
          </div>
        )}
      </div>
    </div>
  );
};

export default OrderPage;
