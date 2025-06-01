import React from 'react';

const AdminDashboard = () => {
  return (
    <div style={{ width: '100%', height: '100%' }}>
      {/* Header */}
      <div style={{ marginBottom: '30px' }}>
        <h1 style={{ fontSize: '28px', fontWeight: '600', color: '#FF6B35', marginBottom: '8px' }}>
          Dashboard Admin RestoKalel
        </h1>
        <p style={{ color: '#666', fontSize: '16px' }}>
          Kelola pengaturan, notifikasi dan hak akses pengguna RestoKalel di sini.
        </p>
      </div>

      {/* Stats Cards */}
      <div style={{
        display: 'grid',
        gridTemplateColumns: 'repeat(auto-fit, minmax(250px, 1fr))',
        gap: '20px',
        marginBottom: '30px'
      }}>
        <div style={{
          backgroundColor: 'white',
          padding: '24px',
          borderRadius: '12px',
          boxShadow: '0 2px 8px rgba(0,0,0,0.1)',
          border: '1px solid #e9ecef'
        }}>
          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
            <div>
              <h3 style={{ fontSize: '32px', fontWeight: '700', color: '#FF6B35', margin: 0 }}>
                150
              </h3>
              <p style={{ color: '#666', fontSize: '14px', margin: '4px 0 0 0' }}>
                Total Menu
              </p>
            </div>
            <div style={{ fontSize: '32px' }}>🍽️</div>
          </div>
        </div>

        <div style={{
          backgroundColor: 'white',
          padding: '24px',
          borderRadius: '12px',
          boxShadow: '0 2px 8px rgba(0,0,0,0.1)',
          border: '1px solid #e9ecef'
        }}>
          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
            <div>
              <h3 style={{ fontSize: '32px', fontWeight: '700', color: '#28a745', margin: 0 }}>
                89
              </h3>
              <p style={{ color: '#666', fontSize: '14px', margin: '4px 0 0 0' }}>
                Total Pelanggan
              </p>
            </div>
            <div style={{ fontSize: '32px' }}>👥</div>
          </div>
        </div>

        <div style={{
          backgroundColor: 'white',
          padding: '24px',
          borderRadius: '12px',
          boxShadow: '0 2px 8px rgba(0,0,0,0.1)',
          border: '1px solid #e9ecef'
        }}>
          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
            <div>
              <h3 style={{ fontSize: '32px', fontWeight: '700', color: '#ffc107', margin: 0 }}>
                45
              </h3>
              <p style={{ color: '#666', fontSize: '14px', margin: '4px 0 0 0' }}>
                Order Hari Ini
              </p>
            </div>
            <div style={{ fontSize: '32px' }}>📝</div>
          </div>
        </div>

        <div style={{
          backgroundColor: 'white',
          padding: '24px',
          borderRadius: '12px',
          boxShadow: '0 2px 8px rgba(0,0,0,0.1)',
          border: '1px solid #e9ecef'
        }}>
          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
            <div>
              <h3 style={{ fontSize: '32px', fontWeight: '700', color: '#dc3545', margin: 0 }}>
                12
              </h3>
              <p style={{ color: '#666', fontSize: '14px', margin: '4px 0 0 0' }}>
                Kategori
              </p>
            </div>
            <div style={{ fontSize: '32px' }}>📊</div>
          </div>
        </div>
      </div>

      {/* Action Cards */}
      <div style={{
        display: 'grid',
        gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))',
        gap: '20px'
      }}>
        <div style={{
          backgroundColor: 'white',
          padding: '24px',
          borderRadius: '12px',
          boxShadow: '0 2px 8px rgba(0,0,0,0.1)',
          border: '1px solid #e9ecef'
        }}>
          <h3 style={{ fontSize: '18px', fontWeight: '600', color: '#333', marginBottom: '12px' }}>
            Pengaturan Admin
          </h3>
          <p style={{ color: '#666', fontSize: '14px', marginBottom: '20px' }}>
            Kelola pengaturan sistem dan hak akses pengguna
          </p>
          <button style={{
            backgroundColor: '#007bff',
            color: 'white',
            border: 'none',
            padding: '10px 20px',
            borderRadius: '6px',
            fontSize: '14px',
            cursor: 'pointer',
            fontWeight: '500'
          }}>
            Tambah Admin Baru
          </button>
        </div>

        <div style={{
          backgroundColor: 'white',
          padding: '24px',
          borderRadius: '12px',
          boxShadow: '0 2px 8px rgba(0,0,0,0.1)',
          border: '1px solid #e9ecef'
        }}>
          <h3 style={{ fontSize: '18px', fontWeight: '600', color: '#333', marginBottom: '12px' }}>
            Laporan Penjualan
          </h3>
          <p style={{ color: '#666', fontSize: '14px', marginBottom: '20px' }}>
            Lihat laporan penjualan dan analisis bisnis
          </p>
          <button style={{
            backgroundColor: '#28a745',
            color: 'white',
            border: 'none',
            padding: '10px 20px',
            borderRadius: '6px',
            fontSize: '14px',
            cursor: 'pointer',
            fontWeight: '500'
          }}>
            Lihat Laporan
          </button>
        </div>

        <div style={{
          backgroundColor: 'white',
          padding: '24px',
          borderRadius: '12px',
          boxShadow: '0 2px 8px rgba(0,0,0,0.1)',
          border: '1px solid #e9ecef'
        }}>
          <h3 style={{ fontSize: '18px', fontWeight: '600', color: '#333', marginBottom: '12px' }}>
            Backup Data
          </h3>
          <p style={{ color: '#666', fontSize: '14px', marginBottom: '20px' }}>
            Backup dan restore data sistem secara berkala
          </p>
          <button style={{
            backgroundColor: '#ffc107',
            color: '#333',
            border: 'none',
            padding: '10px 20px',
            borderRadius: '6px',
            fontSize: '14px',
            cursor: 'pointer',
            fontWeight: '500'
          }}>
            Backup Sekarang
          </button>
        </div>
      </div>
    </div>
  );
};

export default AdminDashboard;
