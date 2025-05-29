import React, { useState, useEffect } from 'react';
import '../styles/Menu.css';

const MenuPage = () => {
  const [menus, setMenus] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [activeCategory, setActiveCategory] = useState('all');
  const [categories, setCategories] = useState([]);
  const [buyingItems, setBuyingItems] = useState(new Set());

  useEffect(() => {
    fetch("/api/get_menu.php")
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          setMenus(data.data);
          // Ambil kategori unik dari data menu
          const uniqueCategories = [
            { id: 'all', name: 'Semua Menu' },
            ...Array.from(new Set(data.data.map(item => item.kategori_id))).map(id => {
              const item = data.data.find(menu => menu.kategori_id === id);
              return { id, name: item ? item.kategori_nama || `Kategori ${id}` : `Kategori ${id}` };
            })
          ];
          setCategories(uniqueCategories);
        } else {
          setError(data.message || "Gagal mengambil data menu");
        }
      })
      .catch((err) => {
        setError("Gagal mengambil data menu: " + err.message);
      })
      .finally(() => setLoading(false));
  }, []);

  // Fungsi untuk handle pembelian item
  const handleBuyItem = async (menu) => {
    // Set loading state untuk item ini
    setBuyingItems(prev => new Set([...prev, menu.id]));

    try {
      const response = await fetch('/api/add_order.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          menu_id: menu.id,
          nama_menu: menu.nama,
          harga: menu.harga,
          quantity: 1,
          customer_name: 'Guest User', // Bisa diganti dengan sistem login
          customer_phone: '', // Bisa ditambahkan form input
          notes: ''
        })
      });

      const result = await response.json();

      if (result.success) {
        alert(`✅ Berhasil menambahkan "${menu.nama}" ke pesanan!`);
      } else {
        alert(`❌ Gagal menambahkan pesanan: ${result.message}`);
      }
    } catch (error) {
      console.error('Error:', error);
      alert('❌ Terjadi kesalahan saat memproses pesanan');
    } finally {
      // Remove loading state
      setBuyingItems(prev => {
        const newSet = new Set(prev);
        newSet.delete(menu.id);
        return newSet;
      });
    }
  };

  const filteredMenus = activeCategory === 'all'
    ? menus
    : menus.filter(menu => menu.kategori_id === activeCategory);

  if (loading) {
    return <div className="text-center mt-5"><span>Loading...</span></div>;
  }
  if (error) {
    return <div className="alert alert-danger mt-4">{error}</div>;
  }

  return (
    <div className="menu-page">
      <div className="menu-section">
        <div className="text-center mb-5">
          <h2 className="hero-title text-gradient">Menu Restoran</h2>
        </div>

        {/* Menu Categories */}
        <div className="category-filter">
          {categories.map(category => (
            <button
              key={category.id}
              className={`category-btn ${activeCategory === category.id ? 'active' : ''}`}
              onClick={() => setActiveCategory(category.id)}
            >
              {category.name}
            </button>
          ))}
        </div>

        {/* Dish Cards */}
        <div className="row">
          {filteredMenus.map(menu => (
            <div className="col-md-4" key={menu.id}>
              <div className="dish-card">
                <div className="dish-image-container loading" style={{height: '200px', maxHeight: '200px', minHeight: '200px'}}>
                  <img
                    src={menu.image || 'https://via.placeholder.com/400x300/f5f5f5/999999?text=Tidak+Ada+Gambar'}
                    alt={menu.nama}
                    className={`dish-image ${!menu.image ? 'error' : ''}`}
                    style={{height: '100%', maxHeight: '200px', objectFit: 'cover'}}
                    onLoad={(e) => {
                      e.target.parentNode.classList.remove('loading');
                    }}
                    onError={(e) => {
                      e.target.onerror = null;
                      e.target.src = 'https://via.placeholder.com/400x300/f5f5f5/999999?text=Gambar+Tidak+Tersedia';
                      e.target.classList.add('error');
                      e.target.parentNode.classList.remove('loading');
                    }}
                  />
                  {menu.badge && (
                    <div className="dish-badge">{menu.badge}</div>
                  )}
                </div>
                <div className="dish-content">
                  <h3 className="dish-title">{menu.nama}</h3>
                  <p className="dish-description">{menu.deskripsi}</p>
                  <div className="dish-meta">
                    <div className="dish-price">Rp {parseInt(menu.harga).toLocaleString('id-ID')}</div>
                    <div className="dish-rating">
                      <div className="dish-rating-stars">★★★★★</div>
                      <span>{menu.rating}</span>
                    </div>
                  </div>
                  <button
                    className={`btn-buy ${buyingItems.has(menu.id) ? 'loading' : ''}`}
                    onClick={() => handleBuyItem(menu)}
                    disabled={buyingItems.has(menu.id)}
                  >
                    <span className="icon">🛒</span>
                    {buyingItems.has(menu.id) ? 'Memproses...' : 'Beli Sekarang'}
                  </button>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

export default MenuPage;
