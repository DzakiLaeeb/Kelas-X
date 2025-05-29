import React, { useState, useEffect } from 'react';
import { Card, Table, Form, Row, Col, Button, Alert } from 'react-bootstrap';
import { useAuth } from '../contexts/AuthContext';

const KasirOrderDetailPage = () => {
  const { user } = useAuth();
  const [salesData, setSalesData] = useState([]);
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

  const fetchSalesData = async () => {
    try {
      setLoading(true);
      
      // Sample data untuk detail penjualan
      const sampleData = [
        {
          no: 1,
          tanggal: '2021-03-18',
          menu: 'Es Kelapa Vanilla',
          harga: 18000,
          qty: 2,
          total: 36000
        },
        {
          no: 2,
          tanggal: '2021-03-18',
          menu: 'Ayam Bakar Madu',
          harga: 25000,
          qty: 1,
          total: 25000
        },
        {
          no: 3,
          tanggal: '2021-03-09',
          menu: 'Nasi Goreng Spesial',
          harga: 20000,
          qty: 1,
          total: 20000
        },
        {
          no: 4,
          tanggal: '2021-03-09',
          menu: 'Es Teh Manis',
          harga: 8000,
          qty: 3,
          total: 24000
        }
      ];
      
      setSalesData(sampleData);
      setError('');
    } catch (error) {
      console.error('Error fetching sales data:', error);
      setError('Terjadi kesalahan saat memuat data penjualan');
    } finally {
      setLoading(false);
    }
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    fetchSalesData();
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

  const getTotalSales = () => {
    return salesData.reduce((sum, item) => sum + item.total, 0);
  };

  return (
    <div className="kasir-order-detail-page">
      {error && (
        <Alert variant="danger" className="mb-4">
          {error}
        </Alert>
      )}

      {/* Header */}
      <div className="mb-4">
        <h2 className="mb-0">Detail Penjualan</h2>
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

      {/* Sales Summary */}
      {salesData.length > 0 && (
        <Card className="mb-4">
          <Card.Body>
            <Row>
              <Col md={3}>
                <div className="text-center">
                  <h5 className="text-primary">Total Item</h5>
                  <h3 className="text-primary">{salesData.length}</h3>
                </div>
              </Col>
              <Col md={3}>
                <div className="text-center">
                  <h5 className="text-success">Total Qty</h5>
                  <h3 className="text-success">
                    {salesData.reduce((sum, item) => sum + item.qty, 0)}
                  </h3>
                </div>
              </Col>
              <Col md={6}>
                <div className="text-center">
                  <h5 className="text-warning">Total Penjualan</h5>
                  <h3 className="text-warning">{formatCurrency(getTotalSales())}</h3>
                </div>
              </Col>
            </Row>
          </Card.Body>
        </Card>
      )}

      {/* Sales Detail Table */}
      <Card>
        <Card.Body>
          <div className="table-responsive">
            <Table striped bordered hover>
              <thead className="table-light">
                <tr>
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>Menu</th>
                  <th>Harga</th>
                  <th>Qty</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                {salesData.length === 0 ? (
                  <tr>
                    <td colSpan="6" className="text-center py-4">
                      <div className="text-muted">
                        {loading ? 'Memuat data...' : 'Tidak ada data penjualan'}
                      </div>
                    </td>
                  </tr>
                ) : (
                  salesData.map((item) => (
                    <tr key={item.no}>
                      <td>{item.no}</td>
                      <td>{item.tanggal}</td>
                      <td>{item.menu}</td>
                      <td>{formatCurrency(item.harga)}</td>
                      <td>
                        <span className="badge bg-info">{item.qty}</span>
                      </td>
                      <td>
                        <strong className="text-success">
                          {formatCurrency(item.total)}
                        </strong>
                      </td>
                    </tr>
                  ))
                )}
              </tbody>
              {salesData.length > 0 && (
                <tfoot>
                  <tr className="table-warning">
                    <th colSpan="5" className="text-end">Total Keseluruhan:</th>
                    <th>
                      <strong className="text-success">
                        {formatCurrency(getTotalSales())}
                      </strong>
                    </th>
                  </tr>
                </tfoot>
              )}
            </Table>
          </div>
        </Card.Body>
      </Card>
    </div>
  );
};

export default KasirOrderDetailPage;
