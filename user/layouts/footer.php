</div><!-- /.bp-content -->

<div class="bp-footer">
    <span>© <?= date('Y') ?> <?= htmlspecialchars($pageTitle) ?>. All rights reserved.</span>
    <span><?= htmlspecialchars($pageTitle) ?> — Secure Banking Platform</span>
</div>

</main><!-- /.bp-main -->

<!-- Scripts -->
<script src="../bootstrap/js/popper.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script src="../plugins/file-upload/file-upload-with-preview.min.js"></script>
<script src="../plugins/dropify/dropify.min.js"></script>
<script src="../plugins/blockui/jquery.blockUI.min.js"></script>
<script src="../plugins/apex/apexcharts.min.js"></script>
<script src="../plugins/sweetalerts/sweetalert2.min.js"></script>
<script src="../plugins/sweetalerts/custom-sweetalert.js"></script>
<script src="../plugins/notification/snackbar/snackbar.min.js"></script>
<script src="../assets/js/clipboard/clipboard.min.js"></script>
<script src="../assets/js/forms/custom-clipboard.js"></script>
<script src="../plugins/table/datatable/datatables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/imask/3.4.0/imask.min.js"></script>
<script src="../assets/js/card/card.js"></script>
<script src="../assets/js/users/account-settings.js"></script>
<script src="../assets/js/components/notification/custom-snackbar.js"></script>

<script>
// ── Theme ──
(function(){
    var DM_KEY = 'bp_theme';
    function applyTheme(m){
        if(m === 'dark'){ document.body.classList.add('bp-dark'); }
        else { document.body.classList.remove('bp-dark'); }
        document.documentElement.classList.remove('bp-dark-pre');
        localStorage.setItem(DM_KEY, m);
    }
    applyTheme(localStorage.getItem(DM_KEY) || 'light');
    document.addEventListener('DOMContentLoaded', function(){
        var btn = document.getElementById('bpDmToggle');
        if(btn) btn.addEventListener('click', function(){
            applyTheme(document.body.classList.contains('bp-dark') ? 'light' : 'dark');
        });
    });
})();

// ── Sidebar Toggle ──
document.addEventListener('DOMContentLoaded', function(){
    var sidebar  = document.getElementById('bpSidebar');
    var overlay  = document.getElementById('bpOverlay');
    var toggle   = document.getElementById('bpSidebarToggle');
    if(toggle && sidebar){
        toggle.addEventListener('click', function(){
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        });
    }
    if(overlay){
        overlay.addEventListener('click', function(){
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });
    }

    // ── Sidebar collapsible submenu ──
    var txToggle = document.getElementById('txToggle');
    var txLogs   = document.getElementById('txLogs');
    if(txToggle && txLogs){
        // auto-open if current page is a tx page
        var txPages = ['credit-debit_transaction','wire-transaction','domestic-transaction','loan-transaction','withdrawal-transaction'];
        var currentPage = window.location.pathname.split('/').pop().replace('.php','');
        if(txPages.indexOf(currentPage) > -1){
            txLogs.classList.add('open');
            txToggle.classList.add('open');
        }
        txToggle.addEventListener('click', function(e){
            e.preventDefault();
            txLogs.classList.toggle('open');
            txToggle.classList.toggle('open');
        });
    }

    // ── Dropdowns ──
    function setupDropdown(btnId, menuId){
        var btn  = document.getElementById(btnId);
        var menu = document.getElementById(menuId);
        if(!btn || !menu) return;
        btn.addEventListener('click', function(e){
            e.stopPropagation();
            // close others
            document.querySelectorAll('.bp-dropdown-menu.open').forEach(function(m){ if(m !== menu) m.classList.remove('open'); });
            menu.classList.toggle('open');
        });
    }
    setupDropdown('bpNotifBtn','bpNotifMenu');
    setupDropdown('bpMsgBtn','bpMsgMenu');
    setupDropdown('bpProfileBtn','bpProfileMenu');
    document.addEventListener('click', function(){
        document.querySelectorAll('.bp-dropdown-menu.open').forEach(function(m){ m.classList.remove('open'); });
    });

    // ── Loader ──
    var loader = document.getElementById('bp-loader');
    if(loader) loader.style.display = 'none';
});

// ── Real-time Approval Notifications ──
(function(){
    var NOTIF_KEY   = 'bp_notif_last_id';
    var lastId      = parseInt(localStorage.getItem(NOTIF_KEY) || '0', 10);
    var currency    = '<?= addslashes($currency) ?>';

    function showApprovalToast(n){
        var isCredit   = (n.type === 'Credit' || n.type === 'Deposit');
        var color      = isCredit ? '#10b981' : '#ef4444';
        var icon       = isCredit ? 'ri-arrow-down-circle-fill' : 'ri-arrow-up-circle-fill';
        var amount     = currency + parseFloat(n.amount).toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2});

        var toast = document.createElement('div');
        toast.style.cssText = [
            'position:fixed','bottom:24px','right:24px','z-index:9999',
            'background:var(--bp-surface)','border:1.5px solid '+color,
            'border-radius:14px','padding:14px 18px',
            'display:flex','align-items:center','gap:12px',
            'box-shadow:0 8px 32px rgba(0,0,0,0.18)',
            'min-width:280px','max-width:340px',
            'animation:bpToastIn .35s cubic-bezier(.34,1.56,.64,1) both',
            'cursor:pointer'
        ].join(';');

        toast.innerHTML = '<div style="width:38px;height:38px;border-radius:10px;background:'+color+'22;display:flex;align-items:center;justify-content:center;flex-shrink:0;">'
            + '<i class="'+icon+'" style="color:'+color+';font-size:20px;"></i></div>'
            + '<div style="flex:1;min-width:0;">'
            + '<div style="font-size:.82rem;font-weight:700;color:var(--bp-text);margin-bottom:2px;">'+n.label+'</div>'
            + '<div style="font-size:.78rem;color:'+color+';font-weight:700;">'+amount+'</div>'
            + '<div style="font-size:.72rem;color:var(--bp-text3);margin-top:2px);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">'+n.message+'</div>'
            + '</div>'
            + '<button onclick="this.parentNode.remove()" style="background:none;border:none;color:var(--bp-text3);cursor:pointer;font-size:16px;padding:0 0 0 4px;">&times;</button>';

        toast.addEventListener('click', function(e){ if(e.target.tagName!=='BUTTON') toast.remove(); });
        document.body.appendChild(toast);
        setTimeout(function(){ if(toast.parentNode) toast.remove(); }, 7000);
    }

    // Inject toast animation keyframe once
    if(!document.getElementById('bp-toast-style')){
        var s = document.createElement('style');
        s.id = 'bp-toast-style';
        s.textContent = '@keyframes bpToastIn{from{opacity:0;transform:translateY(20px) scale(.95)}to{opacity:1;transform:translateY(0) scale(1)}}';
        document.head.appendChild(s);
    }

    function poll(){
        fetch('./api/notifications.php?since='+lastId)
            .then(function(r){ return r.json(); })
            .then(function(data){
                if(!data.notifications) return;
                var maxId = lastId;
                data.notifications.forEach(function(n){
                    if(n.id > maxId) maxId = n.id;
                    if(n.id > lastId) showApprovalToast(n);
                });
                if(maxId > lastId){
                    lastId = maxId;
                    localStorage.setItem(NOTIF_KEY, lastId);
                    // Update badge count
                    var badge = document.querySelector('#bpNotifBtn .bp-navbar-badge');
                    if(!badge && data.count > 0){
                        badge = document.createElement('span');
                        badge.className = 'bp-navbar-badge';
                        document.getElementById('bpNotifBtn') && document.getElementById('bpNotifBtn').appendChild(badge);
                    }
                }
            })
            .catch(function(){});
    }

    // Only poll if page has been loaded for >2s (avoid double-loading flicker)
    setTimeout(function(){
        poll();
        setInterval(poll, 30000);
    }, 2000);
})();

// ── DataTables ──
$(document).ready(function(){
    if($.fn.DataTable){
        $('.bp-datatable').DataTable({
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6'l><'col-12 col-sm-6 d-flex justify-content-sm-end mt-sm-0 mt-3'f>>>" +
                   "<'table-responsive'tr>" +
                   "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count mb-sm-0 mb-3'i><'dt--pagination'p>>",
            "oLanguage": {
                "oPaginate": {"sPrevious": "‹ Prev", "sNext": "Next ›"},
                "sInfo": "Showing _START_–_END_ of _TOTAL_",
                "sSearch": "Search:",
                "sLengthMenu": "Show _MENU_"
            },
            "stripeClasses": [],
            "lengthMenu": [7, 10, 20, 50],
            "pageLength": 7
        });
    }
});
</script>

<script>
// Transfer form handler
$("#transfer_form").submit(function(e){
    e.preventDefault();
    $.ajax({
        type: 'POST',
        data: $("#transfer_form").serialize(),
        url: "<?= $web_url.'/include/process-file.php' ?>",
        dataType: 'JSON',
        success: function(response){
            if(response === "error_pin"){
                Swal.fire({ icon: "error", title: "Incorrect OTP", text: "The OTP code you entered is incorrect.", padding: "2em" });
            } else if(response === "balance"){
                Swal.fire({ icon: "error", title: "Insufficient Balance", text: "You don't have enough funds for this transfer.", padding: "2em" });
            } else if(response === "success"){
                $('#thankyouModal').modal({backdrop:'static',keyboard:false});
                setTimeout(function(){ window.location = './success.php'; }, 12000);
            }
        }
    });
    return false;
});
</script>

<?php if(!empty($livechat)): ?>
<script type="text/javascript">
var Tawk_API=Tawk_API||{},Tawk_LoadStart=new Date();
(function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true; s1.src='<?= htmlspecialchars($livechat) ?>'; s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*'); s0.parentNode.insertBefore(s1,s0);
})();
</script>
<?php endif; ?>

<script>
var data = <?= @json_encode($data ?? []); ?>;
function crypto_type(id){
    for(var i=0;i<data.length;i++){
        if(id==data[i].id){ document.getElementById("wallet_address").value=data[i].wallet_address; }
    }
}
try { var firstUpload = new FileUploadWithPreview('myFirstImage'); } catch(e){}
</script>

</body>
</html>
