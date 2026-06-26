<template>
  <div class="home-container">
    <!-- SPLASH SCREEN -->
    <Transition name="splash-fade">
      <div v-if="showSplash" class="splash-screen">
        <div class="splash-bg"></div>
        <div class="splash-overlay"></div>
        <div class="splash-content">
           <h1 class="splash-title">Gender & Development</h1>
           <p class="splash-subtitle">GAD Corner</p>
           <div class="loader-line"></div>
        </div>
      </div>
    </Transition>

  <div class="page">

    <!-- HERO -->
    <section class="hero">
      <div class="hero-overlay"></div>
      <div class="hero-content">
        <span class="hero-badge">Benguet State University</span>
        <h1 class="hero-title">Gender and Development</h1>
        <img src="/images/logo.png" alt="System Logo" class="hero-logo" />
      </div>
    </section>

    <!-- VISION & MISSION -->
    <section class="section vm-section">
      <div class="section-inner">
        <p class="section-tag">University Identity</p>
        <h2 class="section-title">Vision &amp; Mission</h2>
        <div class="vm-grid">
          <div class="vm-card vision">
            <div class="vm-card-label">
              <span class="material-symbols-outlined">visibility</span> Vision
            </div>
            <p class="vm-quote">"A premier university in transformative education, innovative research, inclusive extension services, sustainable development, and stewardship of culture and the environment"</p>
          </div>
          <div class="vm-card mission">
            <div class="vm-card-label">
              <span class="material-symbols-outlined">target</span> Mission
            </div>
            <p class="vm-body">Cultivate resilient and future-ready human capital through excellent teaching, responsive research, proactive and sustainable community engagements, strategic partnerships, and progressive leadership.</p>
          </div>
        </div>

        <!-- GOALS -->
        <div class="goals-label">
          <span>Goals and Objectives</span>
          <div class="goals-divider"></div>
        </div>
        <div class="goals-grid">
          <div
            v-for="(goal, index) in goals"
            :key="index"
            class="goal-card"
            :class="{ active: activeGoal === index }"
          >
            <div class="goal-icon-wrap">
              <span class="material-symbols-outlined">{{ goal.icon }}</span>
            </div>
            <div class="goal-title-text">{{ goal.title }}</div>
            <div class="goal-desc">{{ goal.description }}</div>

            <div v-if="activeGoal === index" class="objective-list">
              <div v-for="(obj, i) in goal.objectives" :key="i" class="obj-item">
                <div class="obj-dot"></div>
                <div class="obj-text">{{ obj }}</div>
              </div>
            </div>

            <button class="toggle-btn" @click="toggleGoal(index)">
              <span class="material-symbols-outlined toggle-icon">
                {{ activeGoal === index ? 'keyboard_arrow_up' : 'keyboard_arrow_down' }}
              </span>
              {{ activeGoal === index ? 'Hide Objectives' : 'See Objectives' }}
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- IMPACT & ANALYTICS -->
    <section class="section impact-section" ref="impactSectionRef">
      <div class="section-inner">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px; flex-wrap: wrap; gap: 16px;">
          <div>
            <p class="section-tag" style="color: #06b6d4;">University Impact</p>
            <h2 class="section-title" style="margin-bottom: 0; color: #fff;">Gender-Disaggregated Data</h2>
          </div>
          <select v-model="analyticsYear" class="impact-year-select">
            <option v-for="year in availableYears" :key="year" :value="year">{{ year }}</option>
          </select>
        </div>

        <div v-if="analyticsLoading" class="impact-loading">
          <span class="material-symbols-outlined spin-icon">refresh</span>
          <p>Loading analytics...</p>
        </div>
        
        <div v-else class="impact-dashboard">
          
          <!-- Key Metrics -->
          <div class="impact-metrics">
            <div class="metric-card">
              <div class="metric-icon-wrap" style="background: rgba(153, 13, 209, 0.1); color: #c084fc;">
                <span class="material-symbols-outlined">groups</span>
              </div>
              <div class="metric-value">{{ displayTotal }}</div>
              <div class="metric-label">Total Participants</div>
            </div>
            
            <div class="metric-card">
              <div class="metric-icon-wrap" style="background: rgba(6, 182, 212, 0.1); color: #22d3ee;">
                <span class="material-symbols-outlined">man</span>
              </div>
              <div class="metric-value" style="color: #22d3ee;">{{ displayMale }}</div>
              <div class="metric-label">Total Male</div>
            </div>
            
            <div class="metric-card">
              <div class="metric-icon-wrap" style="background: rgba(192, 132, 252, 0.1); color: #c084fc;">
                <span class="material-symbols-outlined">woman</span>
              </div>
              <div class="metric-value" style="color: #c084fc;">{{ displayFemale }}</div>
              <div class="metric-label">Total Female</div>
            </div>
          </div>

          <!-- Chart & Top Offices -->
          <div class="impact-grid">
            <div class="impact-chart-card">
              <h3 class="impact-card-title">Distribution Ratio</h3>
              <div class="doughnut-wrap">
                <Doughnut v-if="yearlyTotal > 0" :data="chartData" :options="chartOptions" />
                <div v-else class="no-data-msg">No data available for {{ analyticsYear }}</div>
              </div>
            </div>
            
            <div class="impact-top-offices">
              <h3 class="impact-card-title">Top Contributing Offices</h3>
              <div v-if="displayOffices.length === 0" class="no-data-msg" style="height: 100%; display: flex; align-items: center; justify-content: center;">
                No data available
              </div>
              <div v-else class="top-office-list">
                <div v-for="(office, index) in displayOffices" :key="index" class="top-office-item">
                  <div class="office-rank">#{{ index + 1 }}</div>
                  <div class="office-info">
                    <div class="office-name" :title="office.office">{{ office.office }}</div>
                    <div class="office-stats">
                      <span class="stat-pill male-pill">{{ office.male }} M</span>
                      <span class="stat-pill female-pill">{{ office.female }} F</span>
                    </div>
                  </div>
                  <div class="office-total">{{ office.male + office.female }}</div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- ABOUT -->
    <section class="section about-section">
      <div class="section-inner">
        <div class="about-grid">
          <div class="about-text">
            <p class="about-label">Platform Background</p>
            <h2 class="about-title">GAD Activity Management System</h2>
            <p class="about-body-lg">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </p>
            <p class="about-body">Aliquip do do consectetur ut ut ut consequat ad ea sed commodo sed adipiscing nisi. Ut eiusmod labore elit ullamco ipsum elit eiusmod aliqua ut ipsum.</p>
            <div class="about-btns">
              <button class="btn-primary">Access System</button>
              <button class="btn-outline">User Guidelines</button>
            </div>
          </div>
          <div class="about-img-wrap">
            <div class="about-img-decor"></div>
            <img
              class="about-img"
              alt="GAD AMS Interface"
              src="https://lh3.googleusercontent.com/aida-public/AB6AXuAWfT6cC60lgVDULdoogzB-MqXy2W26P0mWQRwWZlGkE923q72CL7VsdlXrQ3ONVRtPpIAdz0loaZqNv752I6T4tWMpiGjfjFZ5fW24Sfoci_ohBW1qEVV9NE620yhOZ-daOtQ5_Ke3-ynCEfXJtIFHBYzVbaaW-eQSwnmTd5KrJTtgawPgSP7WFqL0f1fSXoc59OanXgqSLeqWF3vqqVO6Hx8TiUmjgINFqNzFOmzF5FN5tO8vU23t87uu5FIfJZCCqJ0rgoBGvnpJ"
            />
          </div>
        </div>
      </div>
    </section>



  </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import api from '../api';
import { Doughnut } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, ArcElement);

const activeGoal = ref(null);

const getPHYear = () => {
  return new Date(new Date().toLocaleString("en-US", { timeZone: "Asia/Manila" })).getFullYear();
};

const analyticsYear = ref(getPHYear());
const analyticsLoading = ref(true);
const monthlyData = ref([]);
const officeData = ref([]);

// Animation states
const displayTotal = ref(0);
const displayMale = ref(0);
const displayFemale = ref(0);
const displayOffices = ref([]);
const isAnimating = ref(false);
const showSplash = ref(true);

// Scroll observer states
const impactSectionRef = ref(null);
const hasAnimated = ref(false);
const isImpactVisible = ref(false);

const availableYears = computed(() => {
  const startYear = 2026;
  const currentYear = getPHYear();
  const maxYear = Math.max(currentYear + 2, startYear + 2);
  const years = [];
  for (let y = startYear; y <= maxYear; y++) {
    years.push(y);
  }
  return years;
});

const yearlyTotal = computed(() => monthlyData.value.reduce((acc, curr) => acc + curr.male + curr.female, 0));
const yearlyMale = computed(() => monthlyData.value.reduce((acc, curr) => acc + curr.male, 0));
const yearlyFemale = computed(() => monthlyData.value.reduce((acc, curr) => acc + curr.female, 0));

const topOffices = computed(() => {
  if (!officeData.value.length) return [];
  return [...officeData.value]
    .filter(office => (office.male + office.female) > 0)
    .sort((a, b) => (b.male + b.female) - (a.male + a.female));
});

const chartData = computed(() => ({
  labels: ['Male', 'Female'],
  datasets: [
    {
      backgroundColor: ['#22d3ee', '#c084fc'],
      hoverBackgroundColor: ['#06b6d4', '#a855f7'],
      borderWidth: 0,
      data: [displayMale.value, displayFemale.value]
    }
  ]
}));

const chartOptions = {
  animation: false,
  responsive: true,
  maintainAspectRatio: false,
  cutout: '75%',
  plugins: {
    legend: {
      position: 'bottom',
      labels: {
        color: '#1a1a2e',
        font: { family: "'Inter', sans-serif", weight: '600' },
        padding: 20,
        usePointStyle: true
      }
    },
    tooltip: {
      backgroundColor: 'rgba(15, 23, 42, 0.9)',
      titleColor: '#fff',
      bodyColor: '#cbd5e1',
      padding: 12,
      cornerRadius: 8,
      displayColors: true
    }
  }
};

const runAnimation = () => {
  isAnimating.value = true;
  const duration = 2000; // 2 seconds
  const start = performance.now();
  
  const endTotal = yearlyTotal.value;
  const endMale = yearlyMale.value;
  const endFemale = yearlyFemale.value;
  
  const allOffices = topOffices.value;
  displayOffices.value = allOffices; // Show all offices that have participants
  
  const animate = (time) => {
    let progress = (time - start) / duration;
    if (progress > 1) progress = 1;
    
    const easeProgress = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
    
    displayTotal.value = Math.floor(endTotal * easeProgress);
    displayMale.value = Math.floor(endMale * easeProgress);
    displayFemale.value = Math.floor(endFemale * easeProgress);
    
    if (progress < 1) {
      requestAnimationFrame(animate);
    } else {
      isAnimating.value = false;
    }
  };
  
  requestAnimationFrame(animate);
};

const fetchAnalyticsData = async () => {
  analyticsLoading.value = true;
  try {
    const res = await api.get(`analytics/participants/${analyticsYear.value}`);
    if (res.data.success) {
      monthlyData.value = res.data.data;
      officeData.value = res.data.officeData || [];
      hasAnimated.value = false; // Reset animation state on new data
      
      if (isImpactVisible.value) {
        runAnimation();
        hasAnimated.value = true;
      }
    }
  } catch (error) {
    console.error('Failed to load public analytics', error);
  } finally {
    analyticsLoading.value = false;
  }
};

watch(analyticsYear, () => {
  fetchAnalyticsData();
});

onMounted(() => {
  setTimeout(() => {
    showSplash.value = false;
  }, 2500);

  fetchAnalyticsData();
  
  // Intersection Observer to trigger animation when scrolled into view
  const observer = new IntersectionObserver((entries) => {
    isImpactVisible.value = entries[0].isIntersecting;
    if (isImpactVisible.value && !analyticsLoading.value && !hasAnimated.value) {
      runAnimation();
      hasAnimated.value = true;
    }
  }, { threshold: 0.2 }); // Trigger when 20% visible
  
  if (impactSectionRef.value) {
    observer.observe(impactSectionRef.value);
  }
});

const toggleGoal = (idx) => {
  activeGoal.value = activeGoal.value === idx ? null : idx;
};

const goals = [
  {
    title: 'Instruction (Goal 1)',
    description: 'Ensure equity in accessing quality higher education.',
    icon: 'school',
    objectives: [
      'Formulate and implement affirmative action policies aligned with Free Higher Education',
      'Sustain enrollment of disadvantaged students',
      'Sustain grants and scholarships for undergraduate and graduate students',
      'Develop learning continuity and student affairs services plan',
      'Implement inclusive education strategies for diverse learners',
      'Sustain optimal enrollment in all degree programs'
    ]
  },
  {
    title: 'Instruction (Goal 2)',
    description: 'Advance quality and relevant instruction to boost regional economies.',
    icon: 'trending_up',
    objectives: [
      'Enhance instruction through a supportive and innovative environment',
      'Continuously improve tertiary and advanced education standards'
    ]
  },
  {
    title: 'Research (Goal 3)',
    description: 'Develop pioneering science and gender/culture-sensitive solutions.',
    icon: 'biotech',
    objectives: [
      'Strengthen policies, culture, linkages and support for research and development',
      'Advance scholarly capabilities of faculty and staff through research-driven initiatives',
      'Conduct socially-responsive and impactful research'
    ]
  },
  {
    title: 'Extension (Goal 4)',
    description: 'Develop proactive extension programs for disadvantaged communities.',
    icon: 'diversity_1',
    objectives: [
      'Enhance extension system environment for effective community development',
      'Build capacity among extension service providers',
      'Implement culturally-relevant and gender-sensitive engagement programs',
      'Expand reach and inclusivity of extension initiatives',
      'Evaluate and amplify the impact of extension programs'
    ]
  },
  {
    title: 'Governance (Goal 5)',
    description: 'Promote integrity-based governance and efficient management of resources.',
    icon: 'account_balance',
    objectives: [
      'Develop human resource capabilities',
      'Cultivate a culture of good governance and resource stewardship',
      'Develop smart and green campus solutions',
      'Establish sound financial policies and systems'
    ]
  },
  {
    title: 'Business (Goal 6)',
    description: 'Balance progressive resource development while maintaining biophysical resources.',
    icon: 'payments',
    objectives: [
      'Foster a supportive ecosystem for entrepreneurial ventures',
      'Create strategic and socially-responsible business partnerships',
      'Strengthen capabilities in revenue-generating activities',
      'Optimize existing income sources',
      'Pursue green enterprises and growth opportunities'
    ]
  },
  {
    title: 'Partnerships (Goal 7)',
    description: 'Strengthen and expand strategic partnerships.',
    icon: 'handshake',
    objectives: [
      'Build a dynamic and enabling environment for partnerships',
      'Deepen engagement with alumni, government, and civil society',
      'Strengthen multi-sectoral collaboration among academe, LGUs, and communities'
    ]
  }
];
</script>

<style scoped>
/* SPLASH SCREEN */
.splash-screen {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  z-index: 9999;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #0f172a;
  overflow: hidden;
}
.splash-bg {
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  background-image: url('/Welcome.jpg');
  background-size: cover;
  background-position: center;
  opacity: 0.5;
  transform: scale(1.1);
  animation: splashZoom 3s ease-out forwards;
}
.splash-overlay {
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(26, 26, 46, 0.95) 100%);
}
.splash-content {
  position: relative;
  z-index: 1;
  text-align: center;
  animation: splashFadeUp 1s ease-out 0.5s backwards;
}
.splash-title {
  font-size: 48px;
  font-weight: 900;
  color: #fff;
  letter-spacing: 0.05em;
  margin-bottom: 8px;
  text-transform: uppercase;
}
.splash-subtitle {
  font-size: 20px;
  color: #c084fc;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  margin-bottom: 40px;
}
.loader-line {
  width: 200px;
  height: 4px;
  background: rgba(255, 255, 255, 0.1);
  margin: 0 auto;
  border-radius: 4px;
  position: relative;
  overflow: hidden;
}
.loader-line::after {
  content: '';
  position: absolute;
  top: 0; left: 0; bottom: 0;
  width: 50%;
  background: linear-gradient(90deg, #22d3ee, #c084fc);
  border-radius: 4px;
  animation: loaderProgress 2s ease-in-out infinite;
}

@keyframes splashZoom {
  from { transform: scale(1); }
  to { transform: scale(1.05); }
}
@keyframes splashFadeUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}
@keyframes loaderProgress {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(200%); }
}

.splash-fade-leave-active {
  transition: opacity 0.6s ease, transform 0.6s ease;
}
.splash-fade-leave-to {
  opacity: 0;
  transform: scale(1.02);
}

.home-container { box-sizing: border-box; margin: 0; padding: 0; }
.page { background: #f8f6ff; padding-top: 0; }
.hero {
  position: relative;
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 120px 48px 60px;
  text-align: center;
  overflow: hidden;
}
.hero-logo {
  max-width: 450px;
  width: 100%;
  height: auto;
  margin: 30px auto 0;
  display: block;
  transition: transform 0.3s ease, filter 0.3s ease;
}
.hero-logo:hover {
  transform: scale(1.05);
  filter: drop-shadow(0 0 15px rgba(153, 13, 209, 0.6));
}
.hero-overlay {
  position: absolute;
  inset: 0;
  background: radial-gradient(ellipse at 65% 35%, rgba(153, 13, 209, 0.25) 0%, transparent 60%);
  pointer-events: none;
}
.hero-content { position: relative; z-index: 1; max-width: 800px; margin: 0 auto; }
.hero-badge {
  display: inline-block;
  font-size: 16px;
  font-weight: 700;
  letter-spacing: 0.3em;
  text-transform: uppercase;
  color: #cbd5e1;
  border: 1px solid rgba(203, 213, 225, 0.3);
  padding: 6px 20px;
  border-radius: 9999px;
  margin-bottom: 24px;
}
.hero-title {
  font-size: 56px;
  font-weight: 800;
  color: #fff;
  line-height: 1.15;
  letter-spacing: -0.025em;
  margin-bottom: 18px;
}
.hero-title em {
  font-style: normal;
  background: linear-gradient(135deg, #b979cc, #990dd1);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.hero-subtitle {
  font-size: 15px;
  color: #cbd5e1;
  max-width: 540px;
  margin: 0 auto 32px;
  line-height: 1.8;
}
.hero-btns { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
.btn-hero-primary {
  padding: 12px 28px;
  background: linear-gradient(135deg, #990dd1, #b979cc);
  color: #fff;
  border: none;
  border-radius: 9999px;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  cursor: pointer;
  transition: transform 0.2s;
}
.btn-hero-primary:hover { transform: translateY(-2px); }
.btn-hero-outline {
  padding: 12px 28px;
  background: transparent;
  color: #cbd5e1;
  border: 1px solid rgba(203, 213, 225, 0.35);
  border-radius: 9999px;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  cursor: pointer;
  transition: background 0.2s;
}
.btn-hero-outline:hover { background: rgba(203, 213, 225, 0.1); }

.section { padding: 80px 48px; }
.section-inner { max-width: 1160px; margin: 0 auto; }
.section-tag {
  font-size: 16px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.35em;
  color: #990dd1;
  margin-bottom: 10px;
}
.section-title {
  font-size: 44px;
  font-weight: 800;
  color: #1a1a2e;
  letter-spacing: -0.02em;
  line-height: 1.2;
  margin-bottom: 40px;
}

.vm-section { background: #fff; }
.vm-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 64px; }
.vm-card {
  border-radius: 16px;
  padding: 32px;
}
.vm-card.vision {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  border: 1px solid rgba(153, 13, 209, 0.3);
}
.vm-card.mission {
  background: #faf8ff;
  border: 1px solid #ede9f7;
}
.vm-card-label {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  font-weight: 700;
  letter-spacing: 0.3em;
  text-transform: uppercase;
  margin-bottom: 18px;
}
.vm-card.vision .vm-card-label { color: #b979cc; }
.vm-card.mission .vm-card-label { color: #990dd1; }
.vm-card.vision .vm-card-label .material-symbols-outlined { color: #b979cc; font-size: 18px; }
.vm-card.mission .vm-card-label .material-symbols-outlined { color: #990dd1; font-size: 18px; }
.vm-quote {
  font-size: 22px;
  font-weight: 600;
  color: #fff;
  line-height: 1.6;
  font-style: italic;
  border-left: 3px solid #990dd1;
  padding-left: 18px;
}
.vm-body { font-size: 18px; color: #1a1a2e; line-height: 1.85; }

.goals-label {
  display: flex;
  align-items: center;
  gap: 16px;
  margin-bottom: 28px;
}
.goals-label span {
  font-size: 16px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.4em;
  color: #990dd1;
  white-space: nowrap;
}
.goals-divider { flex: 1; height: 1px; background: #ede9f7; }
.goals-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
.goal-card {
  background: #fff;
  border: 1px solid #ede9f7;
  border-radius: 14px;
  padding: 24px;
  display: flex;
  flex-direction: column;
  transition: transform 0.2s, border-color 0.2s;
}
.goal-card:hover { border-color: rgba(153, 13, 209, 0.3); transform: translateY(-3px); }
.goal-card.active { border-color: rgba(153, 13, 209, 0.4); }
.goal-icon-wrap {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 14px;
}
.goal-icon-wrap .material-symbols-outlined { font-size: 20px; color: #b979cc; }
.goal-title-text {
  font-size: 18px;
  font-weight: 1000;
  text-transform: uppercase;
  letter-spacing: 0.06em;
  color: #1a1a2e;
}
.goal-desc { font-size: 15px; color: #16213e; line-height: 1.7; flex-grow: 1; margin-bottom: 14px; }
.objective-list {
  border-top: 1px solid #ede9f7;
  padding-top: 14px;
  margin-top: 4px;
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 14px;
}
.obj-item { display: flex; gap: 8px; align-items: flex-start; }
.obj-dot {
  width: 5px;
  height: 5px;
  border-radius: 50%;
  background: #990dd1;
  margin-top: 6px;
  flex-shrink: 0;
}
.obj-text { font-size: 14px; color: #475569; line-height: 1.6; }
.toggle-btn {
  font-size: 14px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  color: #990dd1;
  background: #faf8ff;
  border: 1px solid rgba(153, 13, 209, 0.2);
  border-radius: 8px;
  padding: 7px 12px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 5px;
  width: fit-content;
  transition: background 0.2s, color 0.2s;
  margin-top: auto;
}
.toggle-btn:hover { background: linear-gradient(135deg, #990dd1, #b979cc); color: #fff; }
.toggle-icon { font-size: 16px; }

/* IMPACT SECTION */
.impact-section {
  background: linear-gradient(135deg, #1a1a2e 0%, #0f172a 100%);
  position: relative;
  overflow: hidden;
  border-top: 1px solid rgba(255, 255, 255, 0.05);
}
.impact-section .section-inner {
  position: relative;
  z-index: 2;
}
.impact-year-select {
  background: rgba(15, 23, 42, 0.8);
  color: #fff;
  border: 1px solid rgba(153, 13, 209, 0.4);
  border-radius: 8px;
  padding: 8px 32px 8px 16px;
  font-size: 15px;
  font-weight: 600;
  outline: none;
  cursor: pointer;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  appearance: none;
  background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23ffffff%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
  background-repeat: no-repeat;
  background-position: right 12px center;
  background-size: 10px auto;
}
.impact-year-select option {
  background: #1e293b;
  color: white;
}
.impact-loading {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 300px;
  color: #94a3b8;
}
.spin-icon {
  font-size: 40px;
  animation: spin 1s linear infinite;
  margin-bottom: 16px;
}
@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.impact-dashboard {
  display: flex;
  flex-direction: column;
  gap: 32px;
}
.impact-metrics {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
}
.metric-card {
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(255, 255, 255, 0.05);
  border-radius: 16px;
  padding: 24px;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  backdrop-filter: blur(10px);
  transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s;
}
.metric-card:hover {
  transform: translateY(-5px);
  border-color: rgba(153, 13, 209, 0.5);
  box-shadow: 0 10px 20px -10px rgba(153, 13, 209, 0.2);
}
.metric-card:hover .material-symbols-outlined {
  animation: iconBounce 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
}
@keyframes iconBounce {
  0% { transform: scale(1); }
  50% { transform: scale(1.2); }
  100% { transform: scale(1); }
}
.metric-icon-wrap {
  width: 56px;
  height: 56px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 16px;
}
.metric-icon-wrap .material-symbols-outlined {
  font-size: 32px;
}
.metric-value {
  font-size: 48px;
  font-weight: 800;
  color: #fff;
  line-height: 1;
  margin-bottom: 8px;
  letter-spacing: -0.02em;
}
.metric-label {
  font-size: 14px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  color: #94a3b8;
}

.impact-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 24px;
}
.impact-chart-card, .impact-top-offices {
  background: rgba(255, 255, 255, 0.02);
  border: 1px solid rgba(255, 255, 255, 0.05);
  border-radius: 16px;
  padding: 32px;
}
.impact-card-title {
  font-size: 18px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 24px;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
  padding-bottom: 16px;
}
.doughnut-wrap {
  position: relative;
  height: 300px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.no-data-msg {
  color: #64748b;
  font-style: italic;
}

.top-office-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}
.top-office-item {
  display: flex;
  align-items: center;
  gap: 16px;
  background: rgba(255, 255, 255, 0.03);
  padding: 16px;
  border-radius: 12px;
  border: 1px solid rgba(255, 255, 255, 0.02);
  transition: transform 0.2s, background 0.2s, border-color 0.2s;
}
.top-office-item:hover {
  transform: translateX(8px);
  background: rgba(255, 255, 255, 0.08);
  border-color: rgba(153, 13, 209, 0.4);
}
.office-rank {
  font-size: 20px;
  font-weight: 800;
  color: #990dd1;
  width: 40px;
  text-align: center;
}
.office-info {
  flex: 1;
  min-width: 0;
}
.office-name {
  font-size: 15px;
  font-weight: 600;
  color: #e2e8f0;
  margin-bottom: 6px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.office-stats {
  display: flex;
  gap: 8px;
}
.stat-pill {
  font-size: 11px;
  font-weight: 700;
  padding: 3px 8px;
  border-radius: 9999px;
  letter-spacing: 0.05em;
}
.male-pill { background: rgba(34, 211, 238, 0.1); color: #22d3ee; }
.female-pill { background: rgba(192, 132, 252, 0.1); color: #c084fc; }

.office-total {
  font-size: 24px;
  font-weight: 800;
  color: #fff;
}

.about-section {
  background: linear-gradient(180deg, #faf8ff 0%, #fff 100%);
  border-top: 1px solid #ede9f7;
}
.about-grid { display: grid; grid-template-columns: 6fr 5fr; gap: 72px; align-items: center; }
.about-text { display: flex; flex-direction: column; gap: 0; }
.about-label {
  font-size: 14px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.35em;
  color: #990dd1;
  margin-bottom: 12px;
}
.about-title {
  font-size: 44px;
  font-weight: 800;
  color: #1a1a2e;
  line-height: 1.2;
  letter-spacing: -0.02em;
  margin-bottom: 20px;
}
.about-body-lg { font-size: 18px; color: #1a1a2e; line-height: 1.85; margin-bottom: 16px; }
.about-body { font-size: 16px; color: #16213e; line-height: 1.85; margin-bottom: 28px; }
.about-btns { display: flex; gap: 12px; flex-wrap: wrap; }
.btn-primary {
  padding: 12px 28px;
  background: linear-gradient(135deg, #990dd1, #b979cc);
  color: #fff;
  border: none;
  border-radius: 9999px;
  font-size: 14px;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  cursor: pointer;
  transition: transform 0.2s;
}
.btn-primary:hover { transform: translateY(-2px); }
.btn-outline {
  padding: 12px 28px;
  background: transparent;
  color: #475569;
  border: 1.5px solid #e2e8f0;
  border-radius: 9999px;
  font-size: 14px;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  cursor: pointer;
  transition: border-color 0.2s, color 0.2s;
}
.btn-outline:hover { border-color: #b979cc; color: #990dd1; }
.about-img-wrap { position: relative; }
.about-img-decor {
  position: absolute;
  inset: -10px;
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  border-radius: 20px;
  opacity: 0.08;
  z-index: 0;
}
.about-img {
  width: 100%;
  border-radius: 16px;
  border: 1px solid #ede9f7;
  position: relative;
  z-index: 1;
  display: block;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.about-img:hover {
  transform: scale(1.02);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}



@media (max-width: 1024px) {
  .vm-grid { grid-template-columns: 1fr; }
  .goals-grid { grid-template-columns: repeat(2, 1fr); }
  .about-grid { grid-template-columns: 1fr; gap: 40px; }
  .impact-grid { grid-template-columns: 1fr; }
  .section { padding: 64px 32px; }
  .hero { padding: 60px 32px 72px; }
}
@media (max-width: 768px) {
  .hero-title { font-size: 36px; }
  .goals-grid { grid-template-columns: 1fr; }
  .stats-bar { grid-template-columns: repeat(2, 1fr); }
  .stats-bar .stat-item:nth-child(2) { border-right: none; }
  .hero-btns, .about-btns { flex-direction: column; align-items: center; }
  .section { padding: 48px 20px; }
  .hero { padding: 48px 20px 60px; }
}
</style>
