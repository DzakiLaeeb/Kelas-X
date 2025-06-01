import React, { useState, useEffect } from 'react';
import { useAuth } from '../contexts/AuthContext';

const OrderDetailPage = () => {
  const { user } = useAuth();
  const [salesData, setSalesData] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [dateFilter, setDateFilter] = useState({
    startDate: '',
    endDate: ''
  });

  useEffect(() => {
    fetchSalesData();
  }, []);

  const fetchSalesData = async () => {
    try {
      setLoading(true);

      // Sample data sesuai dengan gambar
      const sampleData = [
        {
          no: 1,
          faktur: 1,
          tglOrder: '2021-03-09',
          menu: 'Es Teh',
          harga: 2000,
          jumlah: 1,
          total: 2000
        },
        {
          no: 2,
          faktur: 1,
          tglOrder: '2021-03-09',
          menu: 'Bakso Keju',
          harga: 3000,
          jumlah: 2,
          total: 6000
        },
        {
          no: 3,
          faktur: 4,
          tglOrder: '2021-03-18',
          menu: 'Bakso Keju',
          harga: 3000,
          jumlah: 2,
          total: 6000
        },
        {
          no: 4,
          faktur: 4,
          tglOrder: '2021-03-18',
          menu: 'Apel',
          harga: 200,
          jumlah: 1,
          total: 200
        }
      ];

      setSalesData(sampleData);
      setError('');
    } catch (error) {
      console.error('Error fetching sales data:', error);
      setError('Terjadi kesalahan saat memuat data');
    } finally {
      setLoading(false);
    }
  };

  const handleDateChange = (e) => {
    const { name, value } = e.target;
    setDateFilter(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    // Filter data berdasarkan tanggal
    console.log('Filter data dari:', dateFilter.startDate, 'sampai:', dateFilter.endDate);
    // Implementasi filter akan ditambahkan nanti
  };

  const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID').format(amount);
  };

  if (loading) {
    return (
      <div className="text-center mt-5">
        <div className="spinner-border text-primary" role="status">
          <span className="visually-hidden">Loading...</span>
        </div>
        <p className="mt-2">Memuat data penjualan...</p>
      </div>
    );
  }

  return (
    <div style={{ backgroundColor: '#f8f9fa', minHeight: '100vh', padding: '20px' }}>
      <div style={{
        backgroundColor: 'white',
        borderRadius: '8px',
        padding: '30px',
        boxShadow: '0 2px 10px rgba(0,0,0,0.1)'
      }}>
        {error && (
          <div style={{
            backgroundColor: '#f8d7da',
            color: '#721c24',
            padding: '15px',
            borderRadius: '6px',
            border: '1px solid #f5c6cb',
            marginBottom: '20px'
          }}>
            {error}
          </div>
        )}

        <h2 style={{ margin: 0, fontWeight: '300', color: '#FF6B35', marginBottom: '30px' }}>
          Detail Penjualan RestoKalel
        </h2>

        {/* Form Filter Tanggal */}
        <form onSubmit={handleSubmit} style={{ marginBottom: '30px' }}>
          <div style={{
            display: 'grid',
            gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))',
            gap: '20px',
            alignItems: 'end'
          }}>
            <div>
              <label style={{
                display: 'block',
                marginBottom: '5px',
                fontWeight: '500',
                color: '#495057'
              }}>
                Tanggal Awal
              </label>
              <input
                type="date"
                name="startDate"
                value={dateFilter.startDate}
                onChange={handleDateChange}
                style={{
                  width: '100%',
                  padding: '10px',
                  border: '1px solid #ced4da',
                  borderRadius: '4px',
                  fontSize: '14px'
                }}
              />
            </div>
            <div>
              <label style={{
                display: 'block',
                marginBottom: '5px',
                fontWeight: '500',
                color: '#495057'
              }}>
                Tanggal Akhir
              </label>
              <input
                type="date"
                name="endDate"
                value={dateFilter.endDate}
                onChange={handleDateChange}
                style={{
                  width: '100%',
                  padding: '10px',
                  border: '1px solid #ced4da',
                  borderRadius: '4px',
                  fontSize: '14px'
                }}
              />
            </div>
            <div>
              <button
                type="submit"
                style={{
                  padding: '10px 20px',
                  backgroundColor: '#007bff',
                  color: 'white',
                  border: 'none',
                  borderRadius: '4px',
                  fontSize: '14px',
                  cursor: 'pointer'
                }}
              >
                Submit
              </button>
            </div>
          </div>
        </form>

        {/* Tabel Data Penjualan */}
        {loading ? (
          <div style={{
            textAlign: 'center',
            padding: '40px',
            fontSize: '16px',
            color: '#666'
          }}>
            Memuat data...
          </div>
        ) : salesData.length === 0 ? (
          <div style={{
            textAlign: 'center',
            padding: '60px 20px',
            color: '#6c757d'
          }}>
            <div style={{ fontSize: '4rem', marginBottom: '20px' }}>📊</div>
            <h5 style={{ marginBottom: '10px', fontWeight: '500' }}>Belum ada data penjualan</h5>
            <p style={{ margin: 0 }}>Data penjualan akan muncul di sini.</p>
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
                    Faktur
                  </th>
                  <th style={{
                    padding: '15px',
                    textAlign: 'left',
                    borderBottom: '2px solid #dee2e6',
                    fontWeight: '500',
                    color: '#495057'
                  }}>
                    Tgl Order
                  </th>
                  <th style={{
                    padding: '15px',
                    textAlign: 'left',
                    borderBottom: '2px solid #dee2e6',
                    fontWeight: '500',
                    color: '#495057'
                  }}>
                    Menu
                  </th>
                  <th style={{
                    padding: '15px',
                    textAlign: 'left',
                    borderBottom: '2px solid #dee2e6',
                    fontWeight: '500',
                    color: '#495057'
                  }}>
                    Harga
                  </th>
                  <th style={{
                    padding: '15px',
                    textAlign: 'left',
                    borderBottom: '2px solid #dee2e6',
                    fontWeight: '500',
                    color: '#495057'
                  }}>
                    Jumlah
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
                </tr>
              </thead>
              <tbody>
                {salesData.map((item) => (
                  <tr key={item.no} style={{
                    borderBottom: '1px solid #dee2e6',
                    transition: 'background-color 0.2s ease'
                  }}
                  onMouseEnter={(e) => e.target.parentNode.style.backgroundColor = '#f8f9fa'}
                  onMouseLeave={(e) => e.target.parentNode.style.backgroundColor = 'transparent'}
                  >
                    <td style={{ padding: '15px', color: '#495057' }}>
                      {item.no}
                    </td>
                    <td style={{ padding: '15px', color: '#495057', fontWeight: '500' }}>
                      {item.faktur}
                    </td>
                    <td style={{ padding: '15px', color: '#6c757d' }}>
                      {item.tglOrder}
                    </td>
                    <td style={{ padding: '15px', color: '#495057' }}>
                      {item.menu}
                    </td>
                    <td style={{ padding: '15px', color: '#6c757d' }}>
                      {formatCurrency(item.harga)}
                    </td>
                    <td style={{ padding: '15px', color: '#6c757d', textAlign: 'center' }}>
                      {item.jumlah}
                    </td>
                    <td style={{ padding: '15px', color: '#FF6B35', fontWeight: '500' }}>
                      {formatCurrency(item.total)}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>
    </div>
  );
};

export default OrderDetailPage;
