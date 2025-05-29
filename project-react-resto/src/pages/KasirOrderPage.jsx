import React, { useState, useEffect } from 'react';
import { Card, Table, Badge, Button, Alert, Form, Row, Col, Modal } from 'react-bootstrap';
import { useAuth } from '../contexts/AuthContext';

const KasirOrderPage = () => {
  const { user } = useAuth();
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [dateFilter, setDateFilter] = useState({
    startDate: '',
    endDate: ''
  });

  useEffect(() => {
    // Set default dates
    const today = new Date();
    const thirtyDaysAgo = new Date(today);
    thirtyDaysAgo.setDate(today.getDate() - 30);

    setDateFilter({
      startDate: thirtyDaysAgo.toISOString().split('T')[0],
      endDate: today.toISOString().split('T')[0]
    });
  }, []);

  const fetchOrders = async () => {
    try {
      setLoading(true);

      // Sample data sesuai dengan gambar
      const sampleOrders = [
        {
          no: 1,
          faktur: 4,
          pelanggan: 'Cleta Koch',
          tglOrder: '2021-03-18',
          total: 20000,
          bayar: 0,
          kembali: 0,
          status: 'Belum Bayar'
        },
        {
          no: 2,
          faktur: 1,
          pelanggan: 'Elaina Rohan PhD',
          tglOrder: '2021-03-09',
          total: 20000,
          bayar: 50000,
          kembali: 30000,
          status: 'Lunas'
        }
      ];

      setOrders(sampleOrders);
      setError('');
    } catch (error) {
      console.error('Error fetching orders:', error);
      setError('Terjadi kesalahan saat memuat data pesanan');
    } finally {
      setLoading(false);
    }
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    fetchOrders();
  };

  const handleDateChange = (field, value) => {
    setDateFilter(prev => ({
      ...prev,
      [field]: value
    }));
  };

  const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0
    }).format(amount);
  };

  const getStatusButton = (order) => {
    if (order.status === 'Belum Bayar') {
      return (
        <Button
          variant="danger"
          size="sm"
          onClick={() => console.log('Process payment for order', order.faktur)}
        >
          Belum Bayar
        </Button>
      );
    } else {
      return (
        <span className="text-success fw-bold">
          {order.status}
        </span>
      );
    }
  };

  return (
    <div className="kasir-order-page">
      {error && (
        <Alert variant="danger" className="mb-4">
          {error}
        </Alert>
      )}

      {/* Header */}
      <div className="mb-4">
        <h2 className="mb-0">Data Order</h2>
      </div>

      {/* Date Filter Form */}
      <Card className="mb-4">
        <Card.Body>
          <Form onSubmit={handleSubmit}>
            <Row>
              <Col md={3}>
                <Form.Group className="mb-3">
                  <Form.Label>Tanggal Awal</Form.Label>
                  <Form.Control
                    type="date"
                    value={dateFilter.startDate}
                    onChange={(e) => handleDateChange('startDate', e.target.value)}
                    placeholder="mm/dd/yyyy"
                  />
                </Form.Group>
              </Col>
              <Col md={3}>
                <Form.Group className="mb-3">
                  <Form.Label>Tanggal Akhir</Form.Label>
                  <Form.Control
                    type="date"
                    value={dateFilter.endDate}
                    onChange={(e) => handleDateChange('endDate', e.target.value)}
                    placeholder="mm/dd/yyyy"
                  />
                </Form.Group>
              </Col>
              <Col md={3}>
                <Form.Group className="mb-3">
                  <Form.Label>&nbsp;</Form.Label>
                  <div>
                    <Button
                      type="submit"
                      variant="primary"
                      disabled={loading}
                    >
                      {loading ? 'Loading...' : 'Submit'}
                    </Button>
                  </div>
                </Form.Group>
              </Col>
            </Row>
          </Form>
        </Card.Body>
      </Card>

      {/* Orders Table */}
      <Card>
        <Card.Body>
          <div className="table-responsive">
            <Table striped bordered hover>
              <thead className="table-light">
                <tr>
                  <th>No</th>
                  <th>Faktur</th>
                  <th>Pelanggan</th>
                  <th>Tgl Order</th>
                  <th>Total</th>
                  <th>Bayar</th>
                  <th>Kembali</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                {orders.length === 0 ? (
                  <tr>
                    <td colSpan="8" className="text-center py-4">
                      <div className="text-muted">
                        {loading ? 'Memuat data...' : 'Tidak ada data order'}
                      </div>
                    </td>
                  </tr>
                ) : (
                  orders.map((order) => (
                    <tr key={order.no}>
                      <td>{order.no}</td>
                      <td>{order.faktur}</td>
                      <td>{order.pelanggan}</td>
                      <td>{order.tglOrder}</td>
                      <td>{formatCurrency(order.total)}</td>
                      <td>{formatCurrency(order.bayar)}</td>
                      <td>{formatCurrency(order.kembali)}</td>
                      <td>{getStatusButton(order)}</td>
                    </tr>
                  ))
                )}
              </tbody>
            </Table>
          </div>
        </Card.Body>
      </Card>
    </div>
  );
};

export default KasirOrderPage;
