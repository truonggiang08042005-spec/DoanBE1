<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-light">Lịch sử hoạt động</h2>
</div>

<div class="admin-card mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="px-4 py-3" style="width: 180px;">Thời gian</th>
                        <th class="px-4 py-3" style="width: 200px;">Admin</th>
                        <th class="px-4 py-3" style="width: 150px;">Hành động</th>
                        <th class="px-4 py-3">Chi tiết</th>
                        <th class="px-4 py-3 text-center" style="width: 120px;">IP</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-clock-history fs-1 d-block mb-3 opacity-50"></i>
                                Chưa có lịch sử hoạt động nào
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): 
                            // Determine badge color based on action type
                            $actionStr = $log['action_type'];
                            $badgeClass = 'bg-secondary';
                            if (strpos($actionStr, 'CREATE') !== false) {
                                $badgeClass = 'bg-success';
                            } elseif (strpos($actionStr, 'UPDATE') !== false || strpos($actionStr, 'LOCK') !== false || strpos($actionStr, 'REPLY') !== false) {
                                $badgeClass = 'bg-warning text-dark';
                            } elseif (strpos($actionStr, 'DELETE') !== false) {
                                $badgeClass = 'bg-danger';
                            } elseif (strpos($actionStr, 'EXPORT') !== false) {
                                $badgeClass = 'bg-info text-dark';
                            }
                        ?>
                            <tr>
                                <td class="px-4 py-3 text-muted small">
                                    <i class="bi bi-calendar-event me-1"></i> <?= date('d/m/Y', strtotime($log['created_at'])) ?><br>
                                    <i class="bi bi-clock me-1"></i> <?= date('H:i:s', strtotime($log['created_at'])) ?>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="fw-bold text-light"><?= htmlspecialchars($log['fullname']) ?></div>
                                    <div class="text-muted small">@<?= htmlspecialchars($log['username']) ?></div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($log['action_type']) ?></span>
                                </td>
                                <td class="px-4 py-3 text-light">
                                    <?= htmlspecialchars($log['description']) ?>
                                </td>
                                <td class="px-4 py-3 text-center text-muted small">
                                    <?= htmlspecialchars($log['ip_address'] ?? 'N/A') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
<nav aria-label="Page navigation" class="mt-4">
    <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="<?= BASE_URL ?>index.php?controller=admin&action=activityLogs&page=<?= $page - 1 ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link" href="<?= BASE_URL ?>index.php?controller=admin&action=activityLogs&page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <li class="page-item">
                <a class="page-link" href="<?= BASE_URL ?>index.php?controller=admin&action=activityLogs&page=<?= $page + 1 ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<?php endif; ?>
