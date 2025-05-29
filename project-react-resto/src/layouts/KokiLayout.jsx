import React, { useState } from 'react';
import { Outlet, Link, useLocation, useNavigate } from 'react-router-dom';
import { Container, Row, Col, Button } from 'react-bootstrap';
import { useAuth } from '../contexts/AuthContext';

/**
 * Layout untuk role Koki
 * Hanya menampilkan Order Detail di sidebar
 */
const KokiLayout = () => {
  const location = useLocation();
  const navigate = useNavigate();
  const { user, logout } = useAuth();
  const [isSidebarCollapsed, setIsSidebarCollapsed] = useState(false);

  // Menu items untuk koki - hanya order detail
  const kokiMenuItems = [
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
            {/* Logo/Brand */}
            <div className="text-center mb-4">
              <div className="brand-logo">
                <div className="logo-circle bg-warning text-white d-flex align-items-center justify-content-center mx-auto mb-2"
                     style={{width: '60px', height: '60px', borderRadius: '50%'}}>
                  <span style={{fontSize: '24px'}}>🍽️</span>
                </div>
              </div>
            </div>

            {/* Menu Aplikasi Header */}
            <div className="menu-header bg-light p-2 rounded mb-3 text-center">
              <strong>Menu Aplikasi</strong>
            </div>

            {/* Navigation Menu */}
            <div className="sidebar-nav">
              {kokiMenuItems.map((item) => {
                const isActive = location.pathname === `/koki/${item.path}` ||
                                (item.path === '.' && location.pathname === '/koki');

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

          {/* Bottom Section - User Info */}
          <div className="sidebar-bottom">
            <div className="user-info">
              <div className="user-avatar">
                <span className="avatar-icon">👨‍🍳</span>
              </div>
              <div className="user-details">
                <div className="user-name">{user?.name}</div>
                <div className="user-role">Koki</div>
              </div>
              <div className="user-actions">
                <Button
                  variant="outline-danger"
                  size="sm"
                  className="w-100"
                  onClick={handleLogout}
                >
                  Logout
                </Button>
              </div>
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
              <span>Email : {user?.email || 'koki@gmail.com'}</span>
              <span>Level : koki</span>
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

export default KokiLayout;
