<div x-data="{ 
        ping: 0,
        interval: 10000,
        start(){
            setInterval(() => {
                const start = performance.now();
                const response = fetch(`{{ url('ping') }}`);
                const end = (performance.now() - start); 
                const latency = end;
                this.ping = Math.round(latency * 1000);
              }, this.interval);
        },
    }"
    x-init="start()">
    <div 
        :class="{ 'text-green-900' : ping < 500, 'text-yellow-600' : ping > 501 && ping < 2000 , 'text-red-500' : ping > 2001}"
        class="flex items-center gap-1 text-[9px] font-bold text-green-900">
        <span>PING</span>
        <!-- <span class="text-xl">•</span> -->
        <span x-text="ping + 'ms'"></span>
    </div>
</div>