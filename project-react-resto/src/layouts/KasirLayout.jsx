import React, { useState } from 'react';
import { Outlet, Link, useLocation, useNavigate } from 'react-router-dom';
import { Container, Row, Col, Button } from 'react-bootstrap';
import { useAuth } from '../contexts/AuthContext';

/**
 * Layout untuk role Kasir
 * Menampilkan Order dan Order Detail di sidebar
 */
const KasirLayout = () => {
  const location = useLocation();
  const navigate = useNavigate();
  const { user, logout } = useAuth();
  const [isSidebarCollapsed, setIsSidebarCollapsed] = useState(false);

  // Menu items untuk kasir - order dan order detail
  const kasirMenuItems = [
    { path: '.', label: 'Dashboard Kasir', icon: '💰' },
    { path: 'order', label: 'Order', icon: '📝' },
    { path: 'order-detail', label: 'Order Detail', icon: '📋' }
  ];

  const handleLogout = () => {
    logout();
    navigate('/login');
  };

  return (
    <div className="App">
      {/* Sidebar Toggle Button */}
      <button
        className={`sidebar-toggle ${isSidebarCollapsed ? 'collapsed' : ''}`}
        onClick={() => setIsSidebarCollapsed(!isSidebarCollapsed)}
        aria-label="Toggle Sidebar"
      >
        {isSidebarCollapsed ? '→' : '←'}
      </button>

      {/* Sidebar */}
      <div className={`sidebar ${isSidebarCollapsed ? 'collapsed' : ''}`}>
        <div className="sidebar-content">
          {/* Top Section */}
          <div className="sidebar-top">
            {/* Menu Aplikasi Header */}
            <div className="menu-header bg-light p-2 rounded mb-3 text-center">
              <strong>Menu Aplikasi</strong>
            </div>

            {/* Navigation Menu */}
            <div className="sidebar-nav">
              {kasirMenuItems.map((item) => {
                const isActive = location.pathname === `/kasir/${item.path}` ||
                                (item.path === '.' && location.pathname === '/kasir');

                return (
                  <Link
                    key={item.path}
                    to={item.path}
                    className={`sidebar-link ${isActive ? 'active' : ''} d-block p-2 text-decoration-none border-bottom`}
                    style={{color: '#007bff'}}
                  >
                    <span className="sidebar-text">{item.label}</span>
                  </Link>
                );
              })}
            </div>
          </div>


        </div>
      </div>

      {/* Main Content */}
      <div className={`main-content ${isSidebarCollapsed ? 'expanded' : ''}`}>
        {/* Header */}
        <div className="top-header bg-light border-bottom p-3">
          <div className="d-flex justify-content-between align-items-center">
            <div>
              <h4 className="mb-0">Dashboard</h4>
            </div>
            <div className="d-flex align-items-center gap-3">
              <span>Email : {user?.email || 'kasir@gmail.com'}</span>
              <span>Level : kasir</span>
              <Button variant="outline-secondary" size="sm" onClick={handleLogout}>
                Logout
              </Button>
            </div>
          </div>
        </div>

        <Container fluid className="p-4">
          <Outlet />
        </Container>
      </div>
    </div>
  );
};

export default KasirLayout;
