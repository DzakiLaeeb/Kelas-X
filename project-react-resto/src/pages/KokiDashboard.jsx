import React, { useState, useEffect } from 'react';
import { Card, Row, Col, Badge, Button, Alert, Form } from 'react-bootstrap';
import { useAuth } from '../contexts/AuthContext';

/**
 * Dashboard untuk role Koki
 * Menampilkan Detail Penjualan dengan filter tanggal
 */
const KokiDashboard = () => {
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
        <p className="mt-2">Memuat data pesanan...</p>
      </div>
    );
  }

  return (
    <div className="koki-dashboard">
      {error && (
        <Alert variant="danger" className="mb-4">
          {error}
        </Alert>
      )}

      {/* Detail Penjualan */}
      <Card>
        <Card.Body>
          <h4 className="mb-4">Detail Penjualan</h4>

          {/* Form Filter Tanggal */}
          <Form onSubmit={handleSubmit} className="mb-4">
            <Row>
              <Col md={4}>
                <Form.Group className="mb-3">
                  <Form.Label>Tanggal Awal</Form.Label>
                  <Form.Control
                    type="date"
                    name="startDate"
                    value={dateFilter.startDate}
                    onChange={handleDateChange}
                    placeholder="mm/dd/yyyy"
                  />
                </Form.Group>
              </Col>
              <Col md={4}>
                <Form.Group className="mb-3">
                  <Form.Label>Tanggal Akhir</Form.Label>
                  <Form.Control
                    type="date"
                    name="endDate"
                    value={dateFilter.endDate}
                    onChange={handleDateChange}
                    placeholder="mm/dd/yyyy"
                  />
                </Form.Group>
              </Col>
              <Col md={4}>
                <Form.Group className="mb-3">
                  <Form.Label>&nbsp;</Form.Label>
                  <div>
                    <Button type="submit" variant="primary">
                      Submit
                    </Button>
                  </div>
                </Form.Group>
              </Col>
            </Row>
          </Form>

          {/* Tabel Data Penjualan */}
          <div className="table-responsive">
            <table className="table table-striped table-bordered">
              <thead className="table-light">
                <tr>
                  <th>No</th>
                  <th>Faktur</th>
                  <th>Tgl Order</th>
                  <th>Menu</th>
                  <th>Harga</th>
                  <th>Jumlah</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                {salesData.map((item) => (
                  <tr key={item.no}>
                    <td>{item.no}</td>
                    <td>{item.faktur}</td>
                    <td>{item.tglOrder}</td>
                    <td>{item.menu}</td>
                    <td>{formatCurrency(item.harga)}</td>
                    <td>{item.jumlah}</td>
                    <td>{formatCurrency(item.total)}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>

          {salesData.length === 0 && (
            <div className="text-center py-4">
              <div className="display-1">📊</div>
              <h5 className="text-muted">Belum ada data penjualan</h5>
              <p className="text-muted">Data penjualan akan muncul di sini.</p>
            </div>
          )}
        </Card.Body>
      </Card>
    </div>
  );
};

export default KokiDashboard;
