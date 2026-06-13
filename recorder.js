(function () {
    'use strict';

    var clicks = [];
    var maxScroll = 0;
    var startTime = Date.now();
    var sent = false;

    // Track clicks
    document.addEventListener('click', function (e) {
        var x = Math.round(e.pageX);
        var y = Math.round(e.pageY);
        var tag = (e.target && e.target.tagName) ? e.target.tagName.toLowerCase() : '?';
        var t = Math.round((Date.now() - startTime) / 1000);
        clicks.push([x, y, tag, t]);
    });

    // Track scroll (throttled 500ms)
    var scrollThrottle = null;
    window.addEventListener('scroll', function () {
        if (scrollThrottle) return;
        scrollThrottle = setTimeout(function () {
            var scrollH = Math.max(document.documentElement.scrollHeight, document.body.scrollHeight, 1);
            var scrolled = Math.round(((window.scrollY + window.innerHeight) / scrollH) * 100);
            if (scrolled > maxScroll) maxScroll = Math.min(scrolled, 100);
            scrollThrottle = null;
        }, 500);
    }, { passive: true });

    function sendSession() {
        if (sent) return;
        var visitId = window.__visitId;
        if (!visitId || clicks.length === 0) return;
        sent = true;
        var data = JSON.stringify({
            action: 'session',
            visit_id: visitId,
            clicks: clicks,
            max_scroll: maxScroll,
            duration: Math.round((Date.now() - startTime) / 1000),
            screen_w: window.innerWidth || document.documentElement.clientWidth
        });
        try {
            navigator.sendBeacon('tracker.php', new Blob([data], { type: 'application/json' }));
        } catch (e) {
            // fallback
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'tracker.php', false);
            xhr.setRequestHeader('Content-Type', 'application/json');
            try { xhr.send(data); } catch (e2) {}
        }
    }

    window.addEventListener('pagehide', sendSession);
    window.addEventListener('beforeunload', sendSession);
})();
