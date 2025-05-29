import React, { useState, useEffect } from 'react';
import '../styles/Orders.css';

const OrdersPage = () => {
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [statusFilter, setStatusFilter] = useState('');

  useEffect(() => {
    fetchOrders();
  }, [statusFilter]);

  const fetchOrders = async () => {
    setLoading(true);
    try {
      const url = statusFilter 
        ? `/api/get_orders.php?status=${statusFilter}`
        : '/api/get_orders.php';
      
      const response = await fetch(url);
      const data = await response.json();
      
      if (data.success) {
        setOrders(data.data);
      } else {
        setError(data.message || 'Gagal mengambil data pesanan');
      }
    } catch (err) {
      setError('Gagal mengambil data pesanan: ' + err.message);
    } finally {
      setLoading(false);
    }
  };

  const getStatusBadge = (status) => {
    const statusConfig = {
      'pending': { class: 'badge-warning', text: 'Menunggu' },
      'confirmed': { class: 'badge-info', text: 'Dikonfirmasi' },
      'preparing': { class: 'badge-primary', text: 'Diproses' },
      'ready': { class: 'badge-success', text: 'Siap' },
      'delivered': { class: 'badge-success', text: 'Selesai' },
      'cancelled': { class: 'badge-danger', text: 'Dibatalkan' }
    };
    
    const config = statusConfig[status] || { class: 'badge-secondary', text: status };
    return <span className={`status-badge ${config.class}`}>{config.text}</span>;
  };

  if (loading) {
    return (
      <div className="orders-page">
        <div className="text-center mt-5">
          <div className="loading-spinner"></div>
          <p>Memuat pesanan...</p>
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="orders-page">
        <div className="alert alert-danger mt-4">{error}</div>
      </div>
    );
  }

  return (
    <div className="orders-page">
      <div className="container">
        <div className="text-center mb-5">
          <h2 className="hero-title text-gradient">Daftar Pesanan</h2>
          <p className="hero-subtitle">Kelola dan pantau semua pesanan pelanggan</p>
        </div>

        {/* Status Filter */}
        <div className="filter-section mb-4">
          <div className="row">
            <div className="col-md-6">
              <select 
                className="form-select status-filter"
                value={statusFilter}
                onChange={(e) => setStatusFilter(e.target.value)}
              >
                <option value="">Semua Status</option>
                <option value="pending">Menunggu</option>
                <option value="confirmed">Dikonfirmasi</option>
                <option value="preparing">Diproses</option>
                <option value="ready">Siap</option>
                <option value="delivered">Selesai</option>
                <option value="cancelled">Dibatalkan</option>
              </select>
            </div>
            <div className="col-md-6 text-end">
              <button className="btn btn-refresh" onClick={fetchOrders}>
                🔄 Refresh
              </button>
            </div>
          </div>
        </div>

        {/* Orders List */}
        {orders.length === 0 ? (
          <div className="empty-state">
            <div className="empty-icon">📋</div>
            <h3>Belum ada pesanan</h3>
            <p>Pesanan akan muncul di sini setelah pelanggan melakukan pemesanan</p>
          </div>
        ) : (
          <div className="orders-grid">
            {orders.map(order => (
              <div key={order.id} className="order-card">
                <div className="order-header">
                  <div className="order-id">#{order.id}</div>
                  {getStatusBadge(order.status)}
                </div>
                
                <div className="order-content">
                  <div className="order-menu">
                    <div className="menu-image">
                      <img 
                        src={order.menu_image || 'https://via.placeholder.com/80x80/f5f5f5/999999?text=No+Image'} 
                        alt={order.nama_menu}
                        onError={(e) => {
                          e.target.src = 'https://via.placeholder.com/80x80/f5f5f5/999999?text=No+Image';
                        }}
                      />
                    </div>
                    <div className="menu-details">
                      <h4 className="menu-name">{order.nama_menu}</h4>
                      <p className="menu-quantity">Qty: {order.quantity}</p>
                      <p className="menu-price">{order.formatted_price}</p>
                    </div>
                  </div>
                  
                  <div className="customer-info">
                    <h5>Informasi Pelanggan</h5>
                    <p><strong>Nama:</strong> {order.customer_name}</p>
                    {order.customer_phone && (
                      <p><strong>Telepon:</strong> {order.customer_phone}</p>
                    )}
                    {order.notes && (
                      <p><strong>Catatan:</strong> {order.notes}</p>
                    )}
                  </div>
                </div>
                
                <div className="order-footer">
                  <div className="order-date">
                    📅 {order.formatted_date}
                  </div>
                  <div className="order-total">
                    <strong>Total: {order.formatted_price}</strong>
                  </div>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
};

export default OrdersPage;
