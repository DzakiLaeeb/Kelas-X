import React, { useState, useEffect } from 'react';
import { Card, Row, Col, Badge, Button, Alert } from 'react-bootstrap';
import { useAuth } from '../contexts/AuthContext';

/**
 * Dashboard untuk role Kasir
 * Menampilkan ringkasan pesanan dan penjualan
 */
const KasirDashboard = () => {
  const { user } = useAuth();
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [stats, setStats] = useState({
    totalOrders: 0,
    totalRevenue: 0,
    pendingOrders: 0,
    completedOrders: 0
  });

  useEffect(() => {
    fetchOrders();
  }, []);

  const fetchOrders = async () => {
    try {
      setLoading(true);
      const response = await fetch('http://localhost/project-react-resto/api/get_orders.php');
      const result = await response.json();

      if (result.success) {
        const ordersData = result.data || [];
        setOrders(ordersData);

        // Calculate stats
        const totalRevenue = ordersData.reduce((sum, order) => sum + parseFloat(order.total_harga), 0);
        const pendingOrders = ordersData.filter(order =>
          ['pending', 'confirmed', 'preparing'].includes(order.status)
        ).length;
        const completedOrders = ordersData.filter(order =>
          order.status === 'delivered'
        ).length;

        setStats({
          totalOrders: ordersData.length,
          totalRevenue,
          pendingOrders,
          completedOrders
        });
      } else {
        setError(result.message || 'Gagal memuat data pesanan');
      }
    } catch (error) {
      console.error('Error fetching orders:', error);
      setError('Terjadi kesalahan saat memuat data');
    } finally {
      setLoading(false);
    }
  };

  const getStatusBadge = (status) => {
    const statusConfig = {
      pending: { variant: 'warning', text: 'Menunggu' },
      confirmed: { variant: 'info', text: 'Dikonfirmasi' },
      preparing: { variant: 'primary', text: 'Sedang Dimasak' },
      ready: { variant: 'success', text: 'Siap' },
      delivered: { variant: 'secondary', text: 'Dikirim' },
      cancelled: { variant: 'danger', text: 'Dibatalkan' }
    };

    const config = statusConfig[status] || { variant: 'secondary', text: status };
    return <Badge bg={config.variant}>{config.text}</Badge>;
  };

  const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0
    }).format(amount);
  };

  if (loading) {
    return (
      <div className="text-center mt-5">
        <div className="spinner-border text-primary" role="status">
          <span className="visually-hidden">Loading...</span>
        </div>
        <p className="mt-2">Memuat data pesanan...</p>
      </div>
    );
  }

  return (
    <div className="kasir-dashboard">
      {/* Header */}
      <div className="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h2 className="hero-title" style={{ color: '#FF6B35' }}>💰 Dashboard Kasir RestoKalel</h2>
          <p className="text-muted">Selamat datang di RestoKalel, {user?.name}!</p>
        </div>
        <Button variant="outline-primary" onClick={fetchOrders}>
          🔄 Refresh
        </Button>
      </div>

      {error && (
        <Alert variant="danger" className="mb-4">
          {error}
        </Alert>
      )}

      {/* Stats Cards */}
      <Row className="mb-4">
        <Col md={3}>
          <Card className="text-center border-primary">
            <Card.Body>
              <div className="display-4 text-primary">📊</div>
              <h3 className="text-primary">{stats.totalOrders}</h3>
              <p className="text-muted mb-0">Total Pesanan</p>
            </Card.Body>
          </Card>
        </Col>
        <Col md={3}>
          <Card className="text-center border-success">
            <Card.Body>
              <div className="display-4 text-success">💰</div>
              <h3 className="text-success">{formatCurrency(stats.totalRevenue)}</h3>
              <p className="text-muted mb-0">Total Pendapatan</p>
            </Card.Body>
          </Card>
        </Col>
        <Col md={3}>
          <Card className="text-center border-warning">
            <Card.Body>
              <div className="display-4 text-warning">⏳</div>
              <h3 className="text-warning">{stats.pendingOrders}</h3>
              <p className="text-muted mb-0">Pesanan Pending</p>
            </Card.Body>
          </Card>
        </Col>
        <Col md={3}>
          <Card className="text-center border-info">
            <Card.Body>
              <div className="display-4 text-info">✅</div>
              <h3 className="text-info">{stats.completedOrders}</h3>
              <p className="text-muted mb-0">Pesanan Selesai</p>
            </Card.Body>
          </Card>
        </Col>
      </Row>

      {/* Recent Orders */}
      <Card>
        <Card.Header className="d-flex justify-content-between align-items-center">
          <h5 className="mb-0">📋 Pesanan Terbaru</h5>
          <Badge bg="secondary">{orders.length} pesanan</Badge>
        </Card.Header>
        <Card.Body>
          {orders.length === 0 ? (
            <div className="text-center py-4">
              <div className="display-1">🛒</div>
              <h5 className="text-muted">Belum ada pesanan</h5>
              <p className="text-muted">Pesanan akan muncul di sini ketika ada pelanggan yang memesan.</p>
            </div>
          ) : (
            <div className="table-responsive">
              <table className="table table-hover">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Menu</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Pelanggan</th>
                    <th>Status</th>
                    <th>Waktu</th>
                  </tr>
                </thead>
                <tbody>
                  {orders.slice(0, 10).map((order) => (
                    <tr key={order.id}>
                      <td>
                        <Badge bg="light" text="dark">#{order.id}</Badge>
                      </td>
                      <td>
                        <strong>{order.nama_menu}</strong>
                      </td>
                      <td>
                        <Badge bg="info">{order.quantity}x</Badge>
                      </td>
                      <td>
                        <strong className="text-success">
                          {formatCurrency(order.total_harga)}
                        </strong>
                      </td>
                      <td>{order.customer_name}</td>
                      <td>{getStatusBadge(order.status)}</td>
                      <td>
                        <small className="text-muted">
                          {new Date(order.created_at).toLocaleString('id-ID')}
                        </small>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          )}
        </Card.Body>
      </Card>

      {/* Quick Actions */}
      <Row className="mt-4">
        <Col md={4}>
          <Card className="border-primary">
            <Card.Body className="text-center">
              <div className="display-4 text-primary">📝</div>
              <h5>Kelola Pesanan</h5>
              <p className="text-muted">Lihat dan kelola semua pesanan RestoKalel</p>
              <Button variant="primary" href="/kasir/order">
                Buka Order
              </Button>
            </Card.Body>
          </Card>
        </Col>
        <Col md={4}>
          <Card className="border-success">
            <Card.Body className="text-center">
              <div className="display-4 text-success">📋</div>
              <h5>Detail Pesanan</h5>
              <p className="text-muted">Lihat detail lengkap pesanan</p>
              <Button variant="success" href="/kasir/order-detail">
                Buka Order Detail
              </Button>
            </Card.Body>
          </Card>
        </Col>
        <Col md={4}>
          <Card className="border-info">
            <Card.Body className="text-center">
              <div className="display-4 text-info">🔄</div>
              <h5>Refresh Data</h5>
              <p className="text-muted">Perbarui data terbaru</p>
              <Button variant="info" onClick={fetchOrders}>
                Refresh Sekarang
              </Button>
            </Card.Body>
          </Card>
        </Col>
      </Row>
    </div>
  );
};

export default KasirDashboard;
