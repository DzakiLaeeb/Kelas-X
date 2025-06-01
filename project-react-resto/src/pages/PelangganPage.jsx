import React, { useState, useEffect } from 'react';

const PelangganPage = () => {
  const [pelanggan, setPelanggan] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetch("/api/get_customers.php")
      .then((res) => res.json())
      .then((data) => {
        console.log('API Response:', data); // Debug log
        if (data.success) {
          setPelanggan(data.data || []);
        } else {
          setError(data.message || "Gagal mengambil data pelanggan");
        }
      })
      .catch((err) => {
        console.error('Fetch error:', err);
        setError("Gagal mengambil data pelanggan: " + err.message);
      })
      .finally(() => setLoading(false));
  }, []);

  if (loading) {
    return <div className="text-center mt-5"><span>Loading...</span></div>;
  }
  if (error) {
    return <div className="alert alert-danger mt-4">{error}</div>;
  }

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
            Data Pelanggan RestoKalel
          </h2>
          <p style={{ color: '#666', marginBottom: '20px' }}>
            Kelola data pelanggan setia RestoKalel di sini.
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
            Tambah Pelanggan Baru
          </button>
        </div>

        {loading ? (
          <div style={{ textAlign: 'center', padding: '40px' }}>
            <div style={{
              display: 'inline-block',
              width: '40px',
              height: '40px',
              border: '4px solid #f3f3f3',
              borderTop: '4px solid #007bff',
              borderRadius: '50%',
              animation: 'spin 1s linear infinite'
            }}></div>
            <p style={{ marginTop: '15px', color: '#666' }}>Memuat data...</p>
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
            {pelanggan.length === 0 ? (
              <div style={{
                textAlign: 'center',
                padding: '40px',
                color: '#6c757d',
                fontStyle: 'italic'
              }}>
                Tidak ada data pelanggan.
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
                        No
                      </th>
                      <th style={{
                        padding: '15px',
                        textAlign: 'left',
                        borderBottom: '2px solid #dee2e6',
                        fontWeight: '500',
                        color: '#495057'
                      }}>
                        Nama
                      </th>
                      <th style={{
                        padding: '15px',
                        textAlign: 'left',
                        borderBottom: '2px solid #dee2e6',
                        fontWeight: '500',
                        color: '#495057'
                      }}>
                        Alamat
                      </th>
                      <th style={{
                        padding: '15px',
                        textAlign: 'left',
                        borderBottom: '2px solid #dee2e6',
                        fontWeight: '500',
                        color: '#495057'
                      }}>
                        Telepon
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    {pelanggan.map((item, idx) => (
                      <tr key={item.id} style={{
                        borderBottom: '1px solid #dee2e6',
                        transition: 'background-color 0.2s ease'
                      }}
                      onMouseEnter={(e) => e.target.parentNode.style.backgroundColor = '#f8f9fa'}
                      onMouseLeave={(e) => e.target.parentNode.style.backgroundColor = 'transparent'}
                      >
                        <td style={{ padding: '15px', color: '#495057' }}>
                          {idx + 1}
                        </td>
                        <td style={{ padding: '15px', color: '#495057', fontWeight: '500' }}>
                          {item.nama}
                        </td>
                        <td style={{ padding: '15px', color: '#6c757d' }}>
                          {item.alamat}
                        </td>
                        <td style={{ padding: '15px', color: '#6c757d' }}>
                          {item.telp}
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

export default PelangganPage;
