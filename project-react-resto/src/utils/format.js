/**
 * Format a number as Indonesian Rupiah
 * @param {number} price - The price to format
 * @returns {string} - Formatted price string
 */
export const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(price);
};

/**
 * Format a number as a compact string (e.g., 1.5K, 1.2M)
 * @param {number} number - The number to format
 * @returns {string} - Formatted compact string
 */
export const formatCompactNumber = (number) => {
  return new Intl.NumberFormat('id-ID', {
    notation: 'compact',
    compactDisplay: 'short'
  }).format(number);
};
