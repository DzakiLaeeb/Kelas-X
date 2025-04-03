<?php
header('Content-Type: application/json');
require_once '../db_connection.php';

// Clear any previous output
ob_clean();

try {
    // Get POST data
    $order_id = $_POST['order_id'] ?? null;
    $status = $_POST['status'] ?? null;

    // Validate inputs
    if (!$order_id || !$status) {
        throw new Exception('Missing required parameters');
    }

    // Validate status values
    $valid_statuses = ['pending', 'packing', 'shipped', 'review'];
    if (!in_array($status, $valid_statuses)) {
        throw new Exception('Invalid status value');
    }

    // Update database
    $conn = getConnection();
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $result = $stmt->execute([$status, $order_id]);

    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Status updated successfully',
            'status' => $status
        ]);
    } else {
        throw new Exception('Failed to update status');
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

// Make sure nothing else is output
exit();
?>

<!-- Add this modal HTML right before closing </body> tag -->
<div class="modal-overlay" id="statusModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Update Order Status</h3>
            <button class="modal-close" onclick="closeStatusModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="status-options">
                <button class="status-btn" data-status="Pending">
                    <i class="fas fa-clock"></i> Pending
                </button>
                <button class="status-btn" data-status="Sedang Dikemas">
                    <i class="fas fa-box"></i> Sedang Dikemas
                </button>
                <button class="status-btn" data-status="Dikirim">
                    <i class="fas fa-shipping-fast"></i> Dikirim
                </button>
                <button class="status-btn" data-status="Beri Penilaian">
                    <i class="fas fa-star"></i> Beri Penilaian
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Update your JavaScript -->
<script>
function openStatusModal(orderId, row) {
    const modal = document.getElementById('statusModal');
    if (modal) {
        modal.style.display = 'flex';
        modal.dataset.orderId = orderId;
        modal.dataset.rowId = row.dataset.id;
        
        // Add click handlers to status buttons
        document.querySelectorAll('.status-btn').forEach(btn => {
            btn.onclick = () => updateOrderStatus(orderId, btn.dataset.status, row);
        });
    }
}

function closeStatusModal() {
    const modal = document.getElementById('statusModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Edit button click handlers
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const orderId = this.getAttribute('data-id');
            const row = this.closest('tr');
            openStatusModal(orderId, row);
        });
    });

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('statusModal');
        if (event.target === modal) {
            closeStatusModal();
        }
    };
});

function updateOrderStatus(orderId, status, row) {
    fetch('update_order_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            order_id: orderId,
            status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const statusBadge = row.querySelector('.status-badge');
            if (statusBadge) {
                statusBadge.className = `status-badge status-${status}`;
                statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
            }
            showToast('Status updated successfully', 'success');
            closeStatusModal();
        } else {
            showToast(data.message || 'Failed to update status', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error updating status', 'error');
    });
}
</script>