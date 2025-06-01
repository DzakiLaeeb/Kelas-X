import React from 'react';
import { Navigate, useLocation } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';
import { Spinner, Container } from 'react-bootstrap';

const ProtectedRoute = ({
  children,
  requireAdmin = false,
  requireKoki = false,
  requireKasir = false,
  allowedRoles = []
}) => {
  const { isAuthenticated, user, loading, isAdmin, isKoki, isKasir } = useAuth();
  const location = useLocation();

  // Show loading spinner while checking authentication
  if (loading) {
    return (
      <Container className="d-flex justify-content-center align-items-center" style={{ minHeight: '50vh' }}>
        <div className="text-center">
          <Spinner animation="border" role="status" className="mb-3">
            <span className="visually-hidden">Loading...</span>
          </Spinner>
          <p>Checking authentication...</p>
        </div>
      </Container>
    );
  }

  // Redirect to login if not authenticated
  if (!isAuthenticated) {
    return <Navigate to="/login" state={{ from: location }} replace />;
  }

  // Check specific role requirements
  if (requireAdmin && !isAdmin()) {
    return <Navigate to="/unauthorized" replace />;
  }

  if (requireKoki && !isKoki()) {
    return <Navigate to="/unauthorized" replace />;
  }

  if (requireKasir && !isKasir()) {
    return <Navigate to="/unauthorized" replace />;
  }

  // Check allowed roles array
  if (allowedRoles.length > 0 && !allowedRoles.includes(user?.role)) {
    return <Navigate to="/unauthorized" replace />;
  }

  return children;
};

export default ProtectedRoute;
