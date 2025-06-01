import React, { useState } from 'react';
import { Outlet, Link, useLocation, useNavigate } from 'react-router-dom';
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
        zIndex: 1000,
        display: 'flex',
        flexDirection: 'column'
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
            <div style={{
              width: '60px',
              height: '60px',
              borderRadius: '50%',
              backgroundColor: '#ffc107',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              margin: '0 auto 10px',
              fontSize: '24px'
            }}>
              🍽️
            </div>
            <h4 style={{ color: '#FF6B35', margin: 0, fontWeight: '300' }}>
              RestoKalel Koki
            </h4>
          </div>
        )}

        {/* Navigation */}
        <div style={{ padding: '10px 0', flex: 1 }}>
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

          {kokiMenuItems.map((item) => {
            const isActive = location.pathname === `/koki/${item.path}` ||
                           (item.path === '.' && location.pathname === '/koki');

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

        {/* Bottom Section - User Profile */}
        {!isSidebarCollapsed && (
          <div style={{
            marginTop: 'auto',
            padding: '20px',
            borderTop: '1px solid #e9ecef'
          }}>
            <div style={{
              display: 'flex',
              alignItems: 'center',
              marginBottom: '15px',
              padding: '10px',
              backgroundColor: '#f8f9fa',
              borderRadius: '8px'
            }}>
              <span style={{ fontSize: '20px', marginRight: '10px' }}>👨‍🍳</span>
              <div>
                <div style={{ fontSize: '14px', fontWeight: '500', color: '#333' }}>
                  {user?.name}
                </div>
                <div style={{ fontSize: '12px', color: '#666' }}>
                  Koki
                </div>
              </div>
            </div>

            <button
              onClick={handleLogout}
              style={{
                width: '100%',
                padding: '8px 12px',
                backgroundColor: 'transparent',
                color: '#dc3545',
                border: '1px solid #dc3545',
                borderRadius: '4px',
                fontSize: '12px',
                cursor: 'pointer'
              }}
            >
              Logout
            </button>
          </div>
        )}
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
              <h4 style={{ margin: 0, fontWeight: '300', color: '#FF6B35' }}>Dashboard Koki RestoKalel</h4>
            </div>
            <div style={{ display: 'flex', alignItems: 'center', gap: '15px', fontSize: '14px' }}>
              <span style={{ color: '#666' }}>Email: {user?.email || 'koki@gmail.com'}</span>
              <span style={{ color: '#666' }}>Level: koki</span>
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

export default KokiLayout;
