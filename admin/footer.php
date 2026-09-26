<?php
/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  ADMIN PANEL FOOTER
 * ===================================================================== */

declare(strict_types=1);
?>
    </main>
  </div>
</div>

<footer style="text-align:center; padding: 1.25rem 1rem; color: #64748b; font-size: 0.82rem; border-top: 1px solid #e2e8f0; background: #fff; margin-top: auto;">
  <p>&copy; <?= date('Y') ?> <?= e(SCHOOL_NAME) ?>. All rights reserved. &nbsp;·&nbsp; Developed by <a href="https://growthtechnos.com" target="_blank" rel="noopener" style="color: var(--adm-navy, #1e293b); font-weight: 600; text-decoration: underline;">Growth Technos</a></p>
</footer>

<script src="<?= e(url('admin/assets/js/admin.js')) ?>"></script>
</body>
</html>
