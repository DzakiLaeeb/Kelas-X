import React, { useState } from 'react';
import { Outlet, Link, useLocation, useNavigate } from 'react-router-dom';
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
    <div style={{ display: 'flex', minHeight: '100vh' }}>
      {/* Simple Sidebar */}
      <div style={{
        width: isSidebarCollapsed ? '60px' : '250px',
        backgroundColor: 'white',
        borderRight: '1px solid #e9ecef',
        transition: 'width 0.3s ease',
        position: 'fixed',
        height: '100vh',
        overflowY: 'auto',
        zIndex: 1000
      }}>
        {/* Toggle Button */}
        <div style={{ padding: '15px', borderBottom: '1px solid #e9ecef' }}>
          <button
            onClick={() => setIsSidebarCollapsed(!isSidebarCollapsed)}
            style={{
              background: 'none',
              border: 'none',
              fontSize: '18px',
              cursor: 'pointer',
              color: '#666'
            }}
          >
            {isSidebarCollapsed ? '→' : '←'}
          </button>
        </div>

        {/* Brand */}
        {!isSidebarCollapsed && (
          <div style={{ padding: '20px', textAlign: 'center', borderBottom: '1px solid #e9ecef' }}>
            <h4 style={{ color: '#FF6B35', margin: 0, fontWeight: '300' }}>
              RestoKalel Kasir
            </h4>
          </div>
        )}

        {/* Navigation */}
        <div style={{ padding: '10px 0' }}>
          {!isSidebarCollapsed && (
            <div style={{
              padding: '10px 20px',
              fontSize: '0.9rem',
              color: '#666',
              fontWeight: '500',
              borderBottom: '1px solid #f8f9fa',
              marginBottom: '10px'
            }}>
              Menu Aplikasi
            </div>
          )}

          {kasirMenuItems.map((item) => {
            const isActive = location.pathname === `/kasir/${item.path}` ||
                           (item.path === '.' && location.pathname === '/kasir');

            return (
              <Link
                key={item.path}
                to={item.path}
                style={{
                  display: 'flex',
                  alignItems: 'center',
                  padding: isSidebarCollapsed ? '15px 20px' : '12px 20px',
                  textDecoration: 'none',
                  color: isActive ? '#FF6B35' : '#666',
                  backgroundColor: isActive ? '#FFFAF7' : 'transparent',
                  borderLeft: isActive ? '3px solid #FF6B35' : '3px solid transparent',
                  transition: 'all 0.2s ease'
                }}
                onMouseEnter={(e) => {
                  if (!isActive) {
                    e.target.style.backgroundColor = '#f8f9fa';
                  }
                }}
                onMouseLeave={(e) => {
                  if (!isActive) {
                    e.target.style.backgroundColor = 'transparent';
                  }
                }}
              >
                <span style={{ fontSize: '16px', marginRight: isSidebarCollapsed ? '0' : '10px' }}>
                  {item.icon}
                </span>
                {!isSidebarCollapsed && (
                  <span style={{ fontSize: '14px' }}>{item.label}</span>
                )}
              </Link>
            );
          })}
        </div>
      </div>

      {/* Main Content */}
      <div style={{
        marginLeft: isSidebarCollapsed ? '60px' : '250px',
        transition: 'margin-left 0.3s ease',
        backgroundColor: '#f8f9fa',
        minHeight: '100vh'
      }}>
        {/* Header */}
        <div style={{
          backgroundColor: 'white',
          borderBottom: '1px solid #e9ecef',
          padding: '15px 20px'
        }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <div>
              <h4 style={{ margin: 0, fontWeight: '300', color: '#333' }}>Dashboard Kasir</h4>
            </div>
            <div style={{ display: 'flex', alignItems: 'center', gap: '15px', fontSize: '14px' }}>
              <span style={{ color: '#666' }}>Email: {user?.email || 'kasir@gmail.com'}</span>
              <span style={{ color: '#666' }}>Level: kasir</span>
              <button
                onClick={handleLogout}
                style={{
                  padding: '6px 12px',
                  backgroundColor: 'transparent',
                  color: '#6c757d',
                  border: '1px solid #6c757d',
                  borderRadius: '4px',
                  fontSize: '12px',
                  cursor: 'pointer'
                }}
              >
                Logout
              </button>
            </div>
          </div>
        </div>

        <div style={{ padding: '20px' }}>
          <Outlet />
        </div>
      </div>
    </div>
  );
};

export default KasirLayout;
