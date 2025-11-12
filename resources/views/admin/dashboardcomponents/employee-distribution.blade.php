<div class="bg-black p-6 rounded-xl shadow-lg border border-gray-800 overflow-hidden relative">
    <div class="absolute inset-0 z-0 opacity-20" id="matrixCanvas"></div>
    <div class="relative z-10">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold text-purple-400 font-mono">COSMIC QUANTUM MATRIX</h2>
            <div class="text-xs text-cyan-400 font-mono bg-gray-900 px-2 py-1 rounded border border-gray-700" id="timestamp">Your System Performance </div>
        </div>
        
        <div class="flex items-center justify-center relative">
            <!-- Galaxy Nebula Background -->
            <div class="absolute w-[120%] h-[120%] opacity-30">
                <div class="absolute top-1/4 left-1/4 w-32 h-32 rounded-full bg-purple-900 blur-3xl animate-pulse-slow"></div>
                <div class="absolute bottom-1/3 right-1/3 w-40 h-40 rounded-full bg-blue-900 blur-3xl animate-pulse-slower"></div>
            </div>
            
            <div class="w-72 h-72 relative" id="quantumOrbit">
                <!-- Nucleus at center with pulsar effect -->
                <div class="absolute inset-0 m-auto w-20 h-20 rounded-full bg-gradient-to-br from-yellow-500 to-red-600 shadow-lg shadow-yellow-500/30 flex items-center justify-center text-white font-mono text-xs animate-pulse">
                    <span class="glow-text-yellow">NUCLEUS</span>
                </div>
                
                <!-- Galaxy Dust Rings -->
                <div class="absolute inset-0 rounded-full border border-purple-900/50 animate-spin-slow">
                    <div class="absolute inset-0 rounded-full bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-purple-900/10 via-transparent to-transparent"></div>
                </div>
                <div class="absolute inset-8 rounded-full border border-blue-900/50 animate-spin-medium">
                    <div class="absolute inset-0 rounded-full bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-blue-900/10 via-transparent to-transparent"></div>
                </div>
                <div class="absolute inset-16 rounded-full border border-cyan-900/50 animate-spin-slower">
                    <div class="absolute inset-0 rounded-full bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-cyan-900/10 via-transparent to-transparent"></div>
                </div>
                
                <!-- Orbiting particles with comet tails -->
                <div class="absolute top-0 left-1/2 w-4 h-4 rounded-full bg-blue-400 transform -translate-x-1/2 -translate-y-1/2 animate-orbit-slow shadow-lg shadow-blue-500/50 flex items-center justify-center text-white text-xxs particle-comet">
                    e⁻
                </div>
                <div class="absolute top-1/2 right-0 w-4 h-4 rounded-full bg-red-400 transform translate-x-1/2 -translate-y-1/2 animate-orbit-medium shadow-lg shadow-red-500/50 flex items-center justify-center text-white text-xxs particle-comet">
                    p⁺
                </div>
                <div class="absolute bottom-0 left-1/2 w-4 h-4 rounded-full bg-purple-400 transform -translate-x-1/2 translate-y-1/2 animate-orbit-slow-reverse shadow-lg shadow-purple-500/50 flex items-center justify-center text-white text-xxs particle-comet">
                    n⁰
                </div>
                
                <!-- Central data display -->
                <div class="absolute inset-0 flex flex-col items-center justify-center text-white font-mono z-20">
  <div class="text-5xl font-bold mb-1 glow-text-cyan" id="dynamicValue">87.5%</div>
                    <div class="text-xs uppercase tracking-widest mt-2 text-cyan-300" id="metricLabel">QUANTUM PERFORMANCE</div>
                    <div class="absolute bottom-8 text-xxs text-cyan-500/80" id="subLabel">STELLAR SYNCHRONIZATION</div>
                </div>
                
                <!-- Distant stars -->
                <div class="absolute top-1/4 left-1/4 w-1 h-1 rounded-full bg-white/80 animate-twinkle"></div>
                <div class="absolute top-1/3 right-1/4 w-1 h-1 rounded-full bg-blue-300/80 animate-twinkle-delay"></div>
                <div class="absolute bottom-1/4 left-1/3 w-1 h-1 rounded-full bg-purple-300/80 animate-twinkle"></div>
            </div>
        </div>
        
<div class="mt-8 grid grid-cols-3 gap-4 text-center">
  <div class="bg-gray-900/50 p-3 rounded-lg border border-blue-800/80 hover:border-blue-400 transition-all duration-300 hover:shadow-lg hover:shadow-blue-400/10 cosmic-card">
    <div class="text-blue-300 text-xs mb-1">DNS Resolution</div>
    <div class="text-white font-mono text-xl" id="dnsResolution">60 ms</div>
    <div class="text-xxs text-blue-400/60 mt-1" id="dnsLabel">Acceptable</div>
  </div>
  <div class="bg-gray-900/50 p-3 rounded-lg border border-red-800/80 hover:border-red-400 transition-all duration-300 hover:shadow-lg hover:shadow-red-400/10 cosmic-card">
    <div class="text-red-300 text-xs mb-1">Server Load</div>
    <div class="text-white font-mono text-xl" id="serverLoad">1.25 (1m avg)</div>
    <div class="text-xxs text-red-400/60 mt-1" id="loadLabel">Light load</div>
  </div>
  <div class="bg-gray-900/50 p-3 rounded-lg border border-purple-800/80 hover:border-purple-400 transition-all duration-300 hover:shadow-lg hover:shadow-purple-400/10 cosmic-card">
    <div class="text-purple-300 text-xs mb-1">Caching Layer</div>
    <div class="text-white font-mono text-xl" id="cacheLayer">Redis v7.2</div>
    <div class="text-xxs text-purple-400/60 mt-1" id="cacheLabel">Used for session & query caching</div>
  </div>
</div>
        
        <!-- Cosmic status bar -->
        <div class="mt-6 text-xs text-gray-400 font-mono flex justify-between items-center cosmic-status">
            <div id="atomicStatus">GALACTIC STATE: <span class="text-green-400">HARMONIC</span></div>
            <div id="spinIndicator">SPIN: <span class="text-blue-400">↑↓</span></div>
            <div id="energyLevel">ENERGY: <span class="text-yellow-400">7.3KeV</span></div>
        </div>
    </div>
</div>

<style>
    /* Cosmic Animations */
    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    @keyframes spin-medium {
        from { transform: rotate(0deg); }
        to { transform: rotate(-360deg); }
    }
    @keyframes spin-slower {
        from { transform: rotate(0deg); }
        to { transform: rotate(720deg); }
    }
    .animate-spin-slow {
        animation: spin-slow 20s linear infinite;
    }
    .animate-spin-medium {
        animation: spin-medium 15s linear infinite;
    }
    .animate-spin-slower {
        animation: spin-slower 30s linear infinite;
    }
    
    @keyframes orbit-slow {
        0% { transform: translateX(-50%) translateY(-50%) rotate(0deg) translateX(140px) rotate(0deg); }
        100% { transform: translateX(-50%) translateY(-50%) rotate(360deg) translateX(140px) rotate(-360deg); }
    }
    .animate-orbit-slow {
        animation: orbit-slow 12s linear infinite;
    }
    
    @keyframes orbit-medium {
        0% { transform: translateX(-50%) translateY(-50%) rotate(0deg) translateX(100px) rotate(0deg); }
        100% { transform: translateX(-50%) translateY(-50%) rotate(360deg) translateX(100px) rotate(-360deg); }
    }
    .animate-orbit-medium {
        animation: orbit-medium 8s linear infinite;
    }
    
    @keyframes orbit-slow-reverse {
        0% { transform: translateX(-50%) translateY(-50%) rotate(0deg) translateX(80px) rotate(0deg); }
        100% { transform: translateX(-50%) translateY(-50%) rotate(-360deg) translateX(80px) rotate(360deg); }
    }
    .animate-orbit-slow-reverse {
        animation: orbit-slow-reverse 15s linear infinite;
    }
    
    @keyframes twinkle {
        0%, 100% { opacity: 0.2; }
        50% { opacity: 1; }
    }
    @keyframes twinkle-delay {
        0%, 70% { opacity: 0.1; }
        20%, 50% { opacity: 0.8; }
    }
    .animate-twinkle {
        animation: twinkle 4s ease-in-out infinite;
    }
    .animate-twinkle-delay {
        animation: twinkle-delay 5s ease-in-out infinite;
    }
    
    @keyframes pulse-slow {
        0%, 100% { opacity: 0.3; transform: scale(1); }
        50% { opacity: 0.6; transform: scale(1.05); }
    }
    @keyframes pulse-slower {
        0%, 100% { opacity: 0.2; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(1.1); }
    }
    .animate-pulse-slow {
        animation: pulse-slow 8s ease-in-out infinite;
    }
    .animate-pulse-slower {
        animation: pulse-slower 12s ease-in-out infinite;
    }
    
    /* Cosmic Effects */
    .glow-text-cyan {
        text-shadow: 0 0 10px rgba(34, 211, 238, 0.7);
    }
    .glow-text-yellow {
        text-shadow: 0 0 10px rgba(234, 179, 8, 0.7);
    }
    
    .particle-comet::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 2px;
        background: linear-gradient(90deg, transparent, currentColor);
        transform-origin: left center;
        transform: rotate(180deg) translateX(-2px);
        opacity: 0.7;
    }
    
    .cosmic-card {
        backdrop-filter: blur(4px);
        transition: all 0.3s ease, transform 0.2s ease;
    }
    .cosmic-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px -5px rgba(59, 130, 246, 0.3);
    }
    
    .cosmic-status {
        background: rgba(17, 24, 39, 0.5);
        backdrop-filter: blur(4px);
        padding: 6px 12px;
        border-radius: 9999px;
        border: 1px solid rgba(55, 65, 81, 0.5);
    }
    
    .text-xxs {
        font-size: 0.65rem;
    }
</style>

<script>
    // Cosmic Matrix Background
    const canvas = document.getElementById('matrixCanvas');
    const ctx = canvas.getContext('2d');
    
    function resizeCanvas() {
        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;
        initCosmicMatrix();
    }
    
    function initCosmicMatrix() {
        const cosmicSymbols = ['✦', '✧', '✵', '✷', '⚝', '☄', '♆', '♇', 'e⁻', 'p⁺', 'n⁰', 'H', 'He'];
        const fontSize = 16;
        const columns = canvas.width / fontSize;
        const rainDrops = [];
        
        for (let x = 0; x < columns; x++) {
            rainDrops[x] = Math.random() * -canvas.height;
        }
        
        const draw = () => {
            ctx.fillStyle = 'rgba(0, 0, 0, 0.03)';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            ctx.font = `bold ${fontSize}px monospace`;
            
            for (let i = 0; i < rainDrops.length; i++) {
                const text = cosmicSymbols[Math.floor(Math.random() * cosmicSymbols.length)];
                let color;
                const brightness = Math.random() * 0.7 + 0.3;
                
                if (text === 'e⁻') color = `rgba(96, 165, 250, ${brightness})`;
                else if (text === 'p⁺') color = `rgba(248, 113, 113, ${brightness})`;
                else if (text === 'n⁰') color = `rgba(192, 132, 252, ${brightness})`;
                else if (text.includes('H') || text.includes('He')) color = `rgba(74, 222, 128, ${brightness})`;
                else color = `rgba(255, 255, 255, ${brightness * 0.8})`;
                
                ctx.fillStyle = color;
                ctx.fillText(text, i * fontSize, rainDrops[i]);
                
                if (rainDrops[i] > canvas.height && Math.random() > 0.975) {
                    rainDrops[i] = 0;
                }
                rainDrops[i] += fontSize * (Math.random() * 0.3 + 0.7);
            }
        };
        
        setInterval(draw, 50);
    }
    
    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();
    
    // Cosmic Timestamp
    function updateTimestamp() {
        const now = new Date();
        const formatted = now.toLocaleString('en-GB', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        }).replace(/,/g, '');
        document.getElementById('timestamp').textContent = `SYNC: ${formatted}`;
    }
    setInterval(updateTimestamp, 1000);
    updateTimestamp();
    
    // Cosmic Data Animation
    const cosmicMetrics = [
        { 
            value: '87.5%', 
            label: 'QUANTUM COHERENCE', 
            subLabel: 'STELLAR SYNCHRONIZATION',
            electronCount: '2.4M', 
            electronLabel: 'quantum states',
            protonCount: '1.8M', 
            protonLabel: 'nuclear flux',
            neutronCount: '2.1M',
            neutronLabel: 'dark matter',
            status: 'HARMONIC',
            spin: '↑↓',
            energy: '7.3KeV'
        },
        { 
            value: '1.4GeV', 
            label: 'STELLAR ENERGY', 
            subLabel: 'COSMIC RAYS',
            electronCount: '3.1M', 
            electronLabel: 'quantum states',
            protonCount: '2.6M', 
            protonLabel: 'nuclear flux',
            neutronCount: '2.9M',
            neutronLabel: 'dark matter',
            status: 'EXCITED',
            spin: '↑↑',
            energy: '12.8KeV'
        },
        { 
            value: '0.002%', 
            label: 'DARK ENERGY', 
            subLabel: 'QUANTUM FLUCTUATION',
            electronCount: '0.7M', 
            electronLabel: 'quantum states',
            protonCount: '1.2M', 
            protonLabel: 'nuclear flux',
            neutronCount: '1.8M',
            neutronLabel: 'dark matter',
            status: 'CHAOTIC',
            spin: '↓↑',
            energy: '3.6KeV'
        },
        { 
            value: '96ly', 
            label: 'GALACTIC SCALE', 
            subLabel: 'INTERSTELLAR MEDIUM',
            electronCount: '5.2M', 
            electronLabel: 'quantum states',
            protonCount: '3.0M', 
            protonLabel: 'nuclear flux',
            neutronCount: '4.1M',
            neutronLabel: 'dark matter',
            status: 'STABLE',
            spin: '↑↓',
            energy: '9.1KeV'
        }
    ];
    
    let currentCosmicMetric = 0;
    const rotateCosmicMetrics = () => {
        currentCosmicMetric = (currentCosmicMetric + 1) % cosmicMetrics.length;
        const metric = cosmicMetrics[currentCosmicMetric];
        
        // Animate changes
        const cosmicElements = [
            'dynamicValue', 'metricLabel', 'subLabel',
            'electronCount', 'electronLabel',
            'protonCount', 'protonLabel',
            'neutronCount', 'neutronLabel',
            'atomicStatus', 'spinIndicator', 'energyLevel'
        ];
        
        cosmicElements.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.style.transition = 'opacity 0.3s ease, text-shadow 0.5s ease';
                el.style.opacity = 0;
            }
        });
        
        setTimeout(() => {
            // Update values
            document.getElementById('dynamicValue').textContent = metric.value;
            document.getElementById('metricLabel').textContent = metric.label;
            document.getElementById('subLabel').textContent = metric.subLabel;
            
            document.getElementById('electronCount').textContent = metric.electronCount;
            document.getElementById('electronLabel').textContent = metric.electronLabel;
            
            document.getElementById('protonCount').textContent = metric.protonCount;
            document.getElementById('protonLabel').textContent = metric.protonLabel;
            
            document.getElementById('neutronCount').textContent = metric.neutronCount;
            document.getElementById('neutronLabel').textContent = metric.neutronLabel;
            
            // Update status with color changes
            let statusColor = 'text-green-400';
            if (metric.status === 'EXCITED') statusColor = 'text-yellow-400';
            if (metric.status === 'CHAOTIC') statusColor = 'text-red-400';
            
            document.getElementById('atomicStatus').innerHTML = `GALACTIC STATE: <span class="${statusColor}">${metric.status}</span>`;
            document.getElementById('spinIndicator').innerHTML = `SPIN: <span class="text-blue-400">${metric.spin}</span>`;
            document.getElementById('energyLevel').innerHTML = `ENERGY: <span class="text-yellow-400">${metric.energy}</span>`;
            
            // Fade in with glow effect
            cosmicElements.forEach((id, index) => {
                const el = document.getElementById(id);
                if (el) {
                    setTimeout(() => {
                        el.style.opacity = 1;
                        if (id === 'dynamicValue') {
                            el.style.textShadow = '0 0 12px rgba(34, 211, 238, 0.8)';
                            setTimeout(() => {
                                el.style.textShadow = '0 0 10px rgba(34, 211, 238, 0.7)';
                            }, 500);
                        }
                    }, index * 50);
                }
            });
            
            // Change galaxy nebula colors based on state
            const nebulas = document.querySelectorAll('#quantumOrbit > div[class*="rounded-full border"]');
            if (metric.status === 'CHAOTIC') {
                nebulas[0].classList.add('border-red-900/50');
                nebulas[0].classList.remove('border-purple-900/50');
                nebulas[1].classList.add('border-orange-900/50');
                nebulas[1].classList.remove('border-blue-900/50');
            } else {
                nebulas[0].classList.add('border-purple-900/50');
                nebulas[0].classList.remove('border-red-900/50');
                nebulas[1].classList.add('border-blue-900/50');
                nebulas[1].classList.remove('border-orange-900/50');
            }
        }, 300);
    };
    
    // Rotate metrics every 5 seconds
    setInterval(rotateCosmicMetrics, 5000);
    
    // Add comet tails to particles
    document.querySelectorAll('.particle-comet').forEach(particle => {
        particle.addEventListener('animationiteration', () => {
            particle.style.transform = particle.style.transform; // Force reflow for smooth animation
        });
    });
</script>
<script>const fakeDataSets = [
  {
    dns: { value: "60 ms", label: "Acceptable" },
    load: { value: "1.25 (1m avg)", label: "Light load" },
    cache: { value: "Redis v7.2", label: "Session + Query caching" },
    percent: "87.5%",
    memory: { value: "4.2 GB", label: "Normal usage" },
    cpu: { value: "22%", label: "Low usage" },
    network: { value: "125 Mbps", label: "Stable" },
    uptime: { value: "99.98%", label: "High availability" },
  },
  {
    dns: { value: "85 ms", label: "Slight Delay" },
    load: { value: "2.78 (1m avg)", label: "High load" },
    cache: { value: "Redis v7.0", label: "Low hit ratio" },
    percent: "65.1%",
    memory: { value: "7.8 GB", label: "High usage" },
    cpu: { value: "68%", label: "Moderate usage" },
    network: { value: "89 Mbps", label: "Moderate" },
    uptime: { value: "99.85%", label: "Stable" },
  },
  {
    dns: { value: "48 ms", label: "Excellent" },
    load: { value: "0.89 (1m avg)", label: "Idle" },
    cache: { value: "Redis v7.2", label: "Optimized usage" },
    percent: "93.4%",
    memory: { value: "3.1 GB", label: "Low usage" },
    cpu: { value: "15%", label: "Idle" },
    network: { value: "200 Mbps", label: "High speed" },
    uptime: { value: "99.99%", label: "Excellent" },
  },
  {
    dns: { value: "102 ms", label: "Slow" },
    load: { value: "3.44 (1m avg)", label: "Overloaded" },
    cache: { value: "No cache", label: "Cache server down" },
    percent: "95.9%",
    memory: { value: "9.5 GB", label: "Critical usage" },
    cpu: { value: "92%", label: "Overloaded" },
    network: { value: "45 Mbps", label: "Congested" },
    uptime: { value: "98.76%", label: "Unstable" },
  },
  {
    dns: { value: "72 ms", label: "Good" },
    load: { value: "1.67 (1m avg)", label: "Moderate load" },
    cache: { value: "Redis v7.1", label: "Balanced caching" },
    percent: "78.3%",
    memory: { value: "5.6 GB", label: "Moderate usage" },
    cpu: { value: "45%", label: "Normal usage" },
    network: { value: "150 Mbps", label: "Good" },
    uptime: { value: "99.92%", label: "Reliable" },
  },
  {
    dns: { value: "95 ms", label: "Delayed" },
    load: { value: "2.12 (1m avg)", label: "Elevated load" },
    cache: { value: "Memcached v1.6", label: "Partial caching" },
    percent: "70.8%",
    memory: { value: "6.9 GB", label: "High usage" },
    cpu: { value: "73%", label: "High usage" },
    network: { value: "100 Mbps", label: "Stable" },
    uptime: { value: "99.87%", label: "Stable" },
  },
  {
    dns: { value: "55 ms", label: "Very Good" },
    load: { value: "1.10 (1m avg)", label: "Low load" },
    cache: { value: "Redis v7.2", label: "High hit ratio" },
    percent: "90.2%",
    memory: { value: "3.8 GB", label: "Low usage" },
    cpu: { value: "18%", label: "Idle" },
    network: { value: "180 Mbps", label: "High speed" },
    uptime: { value: "99.97%", label: "Excellent" },
  },
  {
    dns: { value: "120 ms", label: "Very Slow" },
    load: { value: "4.01 (1m avg)", label: "Critical load" },
    cache: { value: "No cache", label: "Cache failure" },
    percent: "90.5%",
    memory: { value: "10.2 GB", label: "Overloaded" },
    cpu: { value: "95%", label: "Critical usage" },
    network: { value: "30 Mbps", label: "Severely congested" },
    uptime: { value: "98.45%", label: "Poor" },
  },
  {
    dns: { value: "65 ms", label: "Good" },
    load: { value: "1.45 (1m avg)", label: "Light load" },
    cache: { value: "Redis v7.2", label: "Efficient caching" },
    percent: "85.7%",
    memory: { value: "4.8 GB", label: "Normal usage" },
    cpu: { value: "30%", label: "Low usage" },
    network: { value: "160 Mbps", label: "Good" },
    uptime: { value: "99.95%", label: "High availability" },
  },
  {
    dns: { value: "88 ms", label: "Slight Delay" },
    load: { value: "2.95 (1m avg)", label: "High load" },
    cache: { value: "Redis v7.0", label: "Suboptimal caching" },
    percent: "82.4%",
    memory: { value: "8.3 GB", label: "High usage" },
    cpu: { value: "80%", label: "High usage" },
    network: { value: "75 Mbps", label: "Moderate" },
    uptime: { value: "99.80%", label: "Stable" },
  },
];

function updateFakeData() {
  const random = Math.floor(Math.random() * fakeDataSets.length);
  const data = fakeDataSets[random];

  document.getElementById("dnsResolution").textContent = data.dns.value;
  document.getElementById("dnsLabel").textContent = data.dns.label;

  document.getElementById("serverLoad").textContent = data.load.value;
  document.getElementById("loadLabel").textContent = data.load.label;

  document.getElementById("cacheLayer").textContent = data.cache.value;
  document.getElementById("cacheLabel").textContent = data.cache.label;

  document.getElementById("dynamicValue").textContent = data.percent;

  document.getElementById("memoryUsage").textContent = data.memory.value;
  document.getElementById("memoryLabel").textContent = data.memory.label;

  document.getElementById("cpuUsage").textContent = data.cpu.value;
  document.getElementById("cpuLabel").textContent = data.cpu.label;

  document.getElementById("networkSpeed").textContent = data.network.value;
  document.getElementById("networkLabel").textContent = data.network.label;

  document.getElementById("uptimeStatus").textContent = data.uptime.value;
  document.getElementById("uptimeLabel").textContent = data.uptime.label;
}

// ⏱️ Simulate every 5 seconds for more dynamic updates
setInterval(updateFakeData, 360000);
updateFakeData(); // Initial run
</script>

<!-- Optional: Add glow effect style -->
<style>
  .glow-text-cyan {
    color: #67e8f9;
    text-shadow: 0 0 5px #67e8f9, 0 0 10px #22d3ee, 0 0 15px #0ea5e9;
  }
</style>