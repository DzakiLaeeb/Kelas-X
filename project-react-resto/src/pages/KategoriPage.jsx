import React, { useState, useEffect } from 'react';
import { kategoriService } from '../services/api';

const KategoriPage = () => {
  const [kategori, setKategori] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetchKategori();
  }, []);

  const fetchKategori = async () => {
    try {
      const timeoutPromise = new Promise((_, reject) =>
        setTimeout(() => reject(new Error('Timeout: Server tidak merespon')), 10000)
      );

      const dataPromise = kategoriService.getAllKategori();
      const data = await Promise.race([dataPromise, timeoutPromise]);

      console.log('Data kategori received:', data); // Debug log

      if (!data) {
        throw new Error('Data kategori kosong');
      }

      setKategori(data);
      setError(null);
    } catch (err) {
      let errorMessage = 'Gagal mengambil data kategori. ';
      if (err.message.includes('Timeout')) {
        errorMessage += 'Server tidak merespon, silakan coba lagi nanti.';
      } else if (err.response?.status === 404) {
        errorMessage += 'Data tidak ditemukan.';
      } else if (!navigator.onLine) {
        errorMessage += 'Periksa koneksi internet Anda.';
      } else {
        errorMessage += 'Terjadi kesalahan, silakan coba lagi.';
      }
      setError(errorMessage);
      console.error('Error:', err);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <div style={{
        display: 'flex',
        justifyContent: 'center',
        alignItems: 'center',
        minHeight: '200px',
        fontSize: '16px',
        color: '#666'
      }}>
        Memuat data...
      </div>
    );
  }

  if (error) {
    return (
      <div style={{
        backgroundColor: '#f8d7da',
        color: '#721c24',
        padding: '15px',
        borderRadius: '6px',
        border: '1px solid #f5c6cb',
        margin: '20px'
      }}>
        {error}
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
        <div style={{
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center',
          marginBottom: '30px'
        }}>
          <h2 style={{ margin: 0, fontWeight: '300', color: '#FF6B35' }}>
            Kategori Menu RestoKalel
          </h2>
          <button style={{
            padding: '10px 20px',
            backgroundColor: '#FF6B35',
            color: 'white',
            border: 'none',
            borderRadius: '6px',
            fontSize: '14px',
            cursor: 'pointer'
          }}>
            Tambah Kategori Baru
          </button>
        </div>

        {loading ? (
          <div style={{
            textAlign: 'center',
            padding: '40px',
            fontSize: '16px',
            color: '#666'
          }}>
            Memuat data kategori RestoKalel...
          </div>
        ) : error ? (
          <div style={{
            backgroundColor: '#FFE4D6',
            color: '#E55A2B',
            padding: '15px',
            borderRadius: '6px',
            border: '1px solid #FF6B35'
          }}>
            {error}
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
                    color: '#495057',
                    width: '10%'
                  }}>
                    No
                  </th>
                  <th style={{
                    padding: '15px',
                    textAlign: 'left',
                    borderBottom: '2px solid #dee2e6',
                    fontWeight: '500',
                    color: '#495057',
                    width: '40%'
                  }}>
                    Nama Kategori
                  </th>
                  <th style={{
                    padding: '15px',
                    textAlign: 'left',
                    borderBottom: '2px solid #dee2e6',
                    fontWeight: '500',
                    color: '#495057',
                    width: '50%'
                  }}>
                    Keterangan
                  </th>
                </tr>
              </thead>
              <tbody>
                {kategori.length > 0 ? (
                  kategori.map((item, index) => (
                    <tr key={item.id} style={{
                      borderBottom: '1px solid #dee2e6',
                      transition: 'background-color 0.2s ease'
                    }}
                    onMouseEnter={(e) => e.target.parentNode.style.backgroundColor = '#f8f9fa'}
                    onMouseLeave={(e) => e.target.parentNode.style.backgroundColor = 'transparent'}
                    >
                      <td style={{ padding: '15px', color: '#495057' }}>
                        {index + 1}
                      </td>
                      <td style={{ padding: '15px', color: '#495057', fontWeight: '500' }}>
                        {item.nama_kategori}
                      </td>
                      <td style={{ padding: '15px', color: '#6c757d' }}>
                        {item.keterangan}
                      </td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan="3" style={{
                      padding: '40px',
                      textAlign: 'center',
                      color: '#6c757d',
                      fontStyle: 'italic'
                    }}>
                      Tidak ada data kategori
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        )}
      </div>
    </div>
  );
};

export default KategoriPage;
