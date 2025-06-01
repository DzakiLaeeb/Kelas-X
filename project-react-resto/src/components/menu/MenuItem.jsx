import React, { useState } from 'react';
import { Card, Badge, Button } from 'react-bootstrap';
import { formatPrice } from '../../utils/format';

const MenuItem = ({ item, onViewDetails }) => {
  const [imageLoaded, setImageLoaded] = useState(false);
  const [imageError, setImageError] = useState(false);

  const handleImageLoad = () => {
    setImageLoaded(true);
  };

  const handleImageError = () => {
    setImageError(true);
    setImageLoaded(true);
  };

  const getImageUrl = (imageUrl) => {
    if (!imageUrl) return null;
    
    // If it's already a full URL
    if (imageUrl.startsWith('http')) {
      return imageUrl;
    }
    
    // If it's a relative path starting with /
    if (imageUrl.startsWith('/')) {
      return imageUrl;
    }
    
    // Otherwise, assume it's in the uploads folder
    return `/uploads/${imageUrl}`;
  };

  return (
    <Card className="menu-card h-100">
      <div 
        className={`menu-card-image ${!imageLoaded ? 'loading' : ''} ${imageError ? 'has-error' : ''}`}
        onClick={() => onViewDetails(item)}
      >
        {item.image && !imageError ? (
          <img
            src={getImageUrl(item.image)}
            alt={item.menu}
            onLoad={handleImageLoad}
            onError={handleImageError}
            loading="lazy"
            className={imageError ? 'error' : ''}
          />
        ) : (
          <div className="menu-card-image-fallback">
            <span>{item.menu ? item.menu[0] : '?'}</span>
          </div>
        )}
        
        <div className="menu-card-badges">
          {item.isNew && <Badge className="badge-new">New</Badge>}
          {item.isPopular && <Badge className="badge-popular">Popular</Badge>}
        </div>

        <div className="menu-card-overlay">
          <Button
            variant="light"
            className="btn-view-details"
            onClick={(e) => {
              e.stopPropagation();
              onViewDetails(item);
            }}
          >
            View Details
          </Button>
        </div>
      </div>

      <div className="menu-card-body">
        <div className="menu-card-header">
          <h3 className="menu-card-title">{item.menu}</h3>
          {item.rating && (
            <div className="menu-card-rating">
              <span className="rating-stars">⭐</span>
              {item.rating}
            </div>
          )}
        </div>

        <p className="menu-card-description">
          {item.deskripsi?.length > 100
            ? `${item.deskripsi.substring(0, 100)}...`
            : item.deskripsi}
        </p>

        <div className="menu-card-footer">
          <div className="menu-card-price">{formatPrice(item.harga)}</div>
          <Button variant="primary" className="btn-add-cart">
            Add to Cart
          </Button>
        </div>

        {item.kategori && (
          <div className="menu-card-meta">
            <span>{item.kategori}</span>
          </div>
        )}
      </div>
    </Card>
  );
};

export default MenuItem;
