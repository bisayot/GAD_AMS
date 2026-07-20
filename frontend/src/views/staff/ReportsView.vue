<template>
  <div class="report-wrapper">
    

<div class="masthead">
  <div class="masthead-top">
    <div>
      <p class="eyebrow">Editable Copy</p>
      <h1>Annual Gender &amp; Development<br>Accomplishment Report</h1>
      
    </div>
    
  </div>
</div>

<div class="info-wrap">
  <div class="info-panel" id="info-panel">
    
    
    <div class="field">
      <label>Fiscal Year</label>
      <div class="val" contenteditable="true" id="f-fiscalyear"></div>
    </div>
    <div class="field">
      <label>Organization</label>
      <div class="val" contenteditable="true" id="f-org">Benguet State University</div>
    </div>
    <div class="field">
      <label>Organization Category</label>
      <div class="val" contenteditable="true" id="f-category">State Universities and Colleges</div>
    </div>
    <div class="field">
      <label>Organization Hierarchy</label>
      <div class="val" contenteditable="true" id="f-hierarchy">Benguet State University</div>
    </div>
    <div class="field stat">
      <label>Total Budget / GAA of Organization</label>
      <div class="val" contenteditable="true" id="f-totalbudget">₱944,962,000.00</div>
    </div>
    <div class="field stat">
      <label>Actual GAD Expenditure</label>
      <div class="val" contenteditable="true" id="f-actualgad">₱124,155,536.87</div>
    </div>
    <div class="field stat">
      <label>Original Budget</label>
      <div class="val" contenteditable="true" id="f-origbudget">₱97,160,028.92</div>
    </div>
    <div class="field computed">
      <label>% Utilization of Budget <span class="auto-tag">auto</span></label>
      <div class="val" id="f-util">127.78%</div>
    </div>
    <div class="field computed">
      <label>% of GAD Expenditure <span class="auto-tag">auto</span></label>
      <div class="val" id="f-pctgad">13.14%</div>
    </div>
  </div>
</div>

<div class="toolbar">
  <div class="left-group">
    <button class="gold" id="btn-archive" style="margin-right: 8px;">Archive</button>
    <button class="gold" id="btn-archives">Archives</button>
  </div>
  <div class="right-group">
    <span class="hint">Row count: <b id="row-count">25</b></span>
  </div>
</div>

<div class="table-wrap">
  <div class="scroller">
    <table id="gad-table">
      <thead>
        <tr>
          <th class="col-num"><span class="colnum">1</span>#</th>
          <th><span class="colnum">2</span>Gender Issue / GAD Mandate</th>
          <th><span class="colnum">3</span>Cause of Gender Issue</th>
          <th><span class="colnum">4</span>GAD Result Statement / Objective</th>
          <th><span class="colnum">5</span>Relevant Org. MFO/PAP/PPA</th>
          <th><span class="colnum">6</span>GAD Activity</th>
          <th><span class="colnum">7</span>Performance Indicators / Targets<em>text + M/F, auto-totaled</em></th>
          <th><span class="colnum">8</span>Actual Result (Outputs/Outcomes)<em>text + M/F, auto-totaled</em></th>
          <th><span class="colnum">9</span>Total Agency Approved Budget<em>line items, auto-totaled</em></th>
          <th><span class="colnum">10</span>Actual Cost / Expenditure<em>line items, auto-totaled</em></th>
          <th><span class="colnum">11</span>Responsible Unit / Office</th>
          <th><span class="colnum">12</span>Variance / Remarks</th>
          <th class="col-actions"></th>
        </tr>
      </thead>
      <tbody id="table-body"></tbody>
      <tfoot>
        
        <tr class="foot-computed">
          <td colspan="8" class="foot-label">Total (Computed sum)</td>
          <td id="computed-budget-total">₱0.00</td>
          <td id="computed-cost-total">₱0.00</td>
          <td colspan="3" id="computed-variance-total"></td>
        </tr>
        
        <tr class="foot-gender">
          <td colspan="6" class="foot-label">Computed headcount reach (live, sum of M/F fields)</td>
          <td id="computed-target-total">Target — M: 0 · F: 0 · Total: 0</td>
          <td id="computed-actual-total">Actual — M: 0 · F: 0 · Total: 0</td>
          <td colspan="5"></td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>





  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';
import api from '../../api';

// ...

const router = useRouter();

onMounted(() => {
  
  const phYear = new Intl.DateTimeFormat('en-US', { year: 'numeric', timeZone: 'Asia/Manila' }).format(new Date());
  if (document.getElementById('f-fiscalyear')) document.getElementById('f-fiscalyear').textContent = phYear;
  
  const btnArchive = document.getElementById('btn-archive');
  if(btnArchive) {
    btnArchive.onclick = async () => {
      try {
        const fiscalYear = document.getElementById('f-fiscalyear').textContent.trim();
        const originalWrapper = document.querySelector('.report-wrapper');
        if (!originalWrapper) {
          Swal.fire({ icon: 'error', title: 'Error', text: 'Report wrapper not found.' });
          return;
        }
        
        const clone = originalWrapper.cloneNode(true);
        
        // Clean up the clone's UI elements before saving
        const toolbar = clone.querySelector('.toolbar');
        if (toolbar) toolbar.remove();
        
        // Remove action columns
        clone.querySelectorAll('.col-actions').forEach(el => el.remove());
        clone.querySelectorAll('td:last-child').forEach(el => {
          // Double check it's an action button cell
          if(el.querySelector('.btn-del')) el.remove();
        });
        
        // Remove contenteditable attributes
        clone.querySelectorAll('[contenteditable]').forEach(el => {
          el.removeAttribute('contenteditable');
        });
        
        // Disable all inputs to make them read-only
        clone.querySelectorAll('input').forEach(el => {
          el.setAttribute('readonly', 'true');
          el.style.pointerEvents = 'none';
          el.style.border = 'none';
          el.style.background = 'transparent';
        });
        
        // Remove the Add Row button row
        const trAdd = clone.querySelector('.tr-add');
        if (trAdd) trAdd.remove();
        
        const htmlContent = clone.outerHTML;
        
        const response = await api.post('annual-reports/archive', {
          fiscal_year: fiscalYear,
          html_content: htmlContent
        });

        if (response.data && response.data.success) {
          Swal.fire({ icon: 'success', title: 'Archived!', text: 'Report archived successfully.', timer: 1500, showConfirmButton: false });
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to archive report.' });
        }
      } catch (error) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while archiving.' });
      }
    };
  }
  const btnArchives = document.getElementById('btn-archives');
  if(btnArchives) {
    btnArchives.onclick = () => {
      router.push('/staff/annual-report-archives');
    };
  }

// ---------------------------------------------------------------------
// Row data transcribed from the original FY2025 GAD Accomplishment Report.
// budget/cost items: [ label, budgetAmount, actualCostAmount, fundSource ]
// indicators / actualResult: { text, m, f }  — m/f left as '' when the
// original report gave no gender-disaggregated figure for that field.
// ---------------------------------------------------------------------
const rows = [
  { section:"CLIENT-FOCUSED ACTIVITIES", issue:"Part VII of CHED Memorandum Order Number 1, series 2015 on Gender-Responsive Research and Extension Program", cause:"Presence of gender inequality, poverty and GAD-related concerns in the community", result:"To sustain GAD-related extension activities delivering technology transfer, Livelihood Program, Technical Assistance, and Advocacy to community partners to help promote gender equality, poverty reduction and sustainable development", org:"MFO: Technical Advisory Extension Program", activity:"Conduct of Extension project/activities to partner organizational/communities as component of Gender Responsive Extension Program (GREP) to partner organization/communities", responsible:"Research and Extension, GAD Office, various offices/colleges in the University/external campuses", remarks:"Done.",
    indicators:{text:"No. of extension activities conducted within the year - 24 Extension project/activities conducted within the year", m:500, f:560},
    actualResult:{text:"51 Extension Activities", m:2098, f:4517},
    items:[
      ["Meals and Snacks",529200,758810,"GAA"],
      ["Materials & Supplies",168000,173117,"GAA"],
      ["PS",182003.85,1364514.37,"GAA"],
      ["Honorarium",34108,34108,"GAA"],
      ["Token",69420,69420,"GAA"],
      ["Transportation/Fuel",36226,36226,"GAA"]
    ]},

  { issue:"Low number of women's participation in sports / MCW-IRR Section 14: Develop, establish and strengthen programs for the participation of women in competitive and non-competitive sports as means to achieve excellence, promote physical and social well-being", cause:"Minimal attendance of female students to competitive and non-competitive sports", result:"To increase female students' level of participation and awareness on Gender in Sports", org:"MFO: Higher Education Program", activity:"Participate in sports activities targeted for female students", responsible:"College of Human Kinetics, GAD Office", remarks:"Done.",
    indicators:{text:"No. of sports activities supported through allocation of budget for sports and socio-cultural activities/E-sports (i.e. Annual Women's Martial Arts Festival) - 2 sports activities", m:'', f:20},
    actualResult:{text:"3 sports activities", m:'', f:''},
    items:[
      ["Registration",80000,0,"GAA"],
      ["PS: 3 coaches, 2 events, 2 days/event",20400.71,165002.92,"GAA"],
      ["Meals/Snacks & Travelling Expenses",50600,100000,"GAA"]
    ]},

  { issue:"Low level of GAD awareness / CHED Memo Order No. 01 series 2015", cause:"Limited activities to increase awareness of men and women students to GAD-related information (1st year students, transferees)", result:"To increase the students' level of awareness and appreciation on GAD", org:"MFO: Higher Education Program\nMFO: Technical Advisory Extension Program\nMFO: Advance Education Program\nMFO: Research Program", activity:"Conduct GAD orientation/forum/seminar to BSU 1st year/transferee students (face to face/online: 14 colleges)", responsible:"GAD Office, Office of Student Services, all colleges, various offices / external campus", remarks:"Done.",
    indicators:{text:"No. of students oriented on GAD - 3,500 students oriented on GAD", m:1000, f:2500},
    actualResult:{text:"2,461 New students oriented", m:882, f:1579},
    items:[
      ["Materials & Supplies",55500,10000,"GAA"],
      ["Meals and Snacks (students): 3,500 pax x ₱80.00",280000,0,"GAA"],
      ["PS Attribution (TWG members, 8 hrs; GFPS-TWG lecturing GAD)",116022.86,0,"GAA"],
      ["Meals & Snacks (facilitators): 60 pax x ₱380 x 6 colleges",136800,38000,"GAA"],
      ["Token: 16 speakers @ ₱1,000/college",16000,0,"GAA"],
      ["PS Attribution",165097.30,165097.30,"GAA"],
      ["Venue",10000,10000,"GAA"]
    ]},

  { issue:"Low participation of student leaders on Gender Mainstreaming / CHED Memo Order No. 01 series 2015", cause:"Student leaders have limited understanding on GAD in the University", result:"To empower student leaders regarding GAD-responsive leadership (La Trinidad Campus, Bokod Campus and Buguias Campus)", org:"MFO: Higher Education Program\nMFO: Advance Education", activity:"Continuous conduct of GAD-responsive leadership training for student leaders", responsible:"GAD Office, Office of Student Services", remarks:"Done.",
    indicators:{text:"No. of trainings conducted to increase GAD awareness and responsiveness of student leaders by December 2025 - 2 trainings (La Trinidad, Bokod, Buguias Campus)", m:75, f:175},
    actualResult:{text:"10 activities conducted for student leaders", m:329, f:360},
    items:[
      ["Materials & Supplies (150 x 100, 2 trainings/pax)",30000,9603,"GAA"],
      ["Lunch: 100 pax x ₱180 x 2",44000,0,"GAA"],
      ["Snack: 250 pax x ₱80 x 2",40000,0,"GAA"],
      ["Token: 8 pax x ₱1,000",8000,5000,"GAA"],
      ["Venue",5000,0,"GAA"],
      ["PS Attribution",147090,147090,"GAA"]
    ]},

  { section:"ORGANIZATION-FOCUSED ACTIVITIES", issue:"Programs on Awards and Incentives for Service Excellence (PRAISE under CSC Res. No. 010112 and CSC MC No. 1, s. 2001); Memo Circular No. 2011-01 (Guidelines for the Creation, Strengthening and Institutionalization of GAD Focal Point System); BOR Res. No. 2316, s. 2014 (Institutionalization of BSU GFPS and GM Framework)", cause:"Low recognition/appreciation of Gender Mainstreaming in BSU", result:"Strengthen Gender Mainstreaming through recognition of GAD implementation in the University", org:"MFO: Research Services\nMFO: Extension Services\nMFO: Advance Education Services\nMFO: Higher Education Services", activity:"Provide recognition and award to GAD implementers and other GAD-related awardees (students and employees, GAD Advocate Award)", responsible:"HRDO, HRMO, BSU-PRAISE Committee, GAD Office", remarks:"Done.",
    indicators:{text:"No. of awards provided through BSU-PRAISE - At least 1 GAD Advocate award via BSU Program on Awards and Incentives for Service Excellence (BSU-PRAISE)", m:'', f:''},
    actualResult:{text:"1 Gender and Development Advocate Award (PRAISE Everlasting Awards 2025) awarded to Office of Student Services", m:'', f:''},
    items:[
      ["PS Attribution",49090,278007.34,"GAA"],
      ["Incentive",5000,5000,"GAA"]
    ]},

  { issue:"Limited application of GAD Mainstreaming (GM) in Instruction, Research, Extension and Production / Magna Carta of Women (RA 9710)", cause:"Low awareness among personnel in the University about GAD mainstreaming", result:"To enhance GAD mainstreaming in Administration, Academic, Research and Extension, Production", org:"MFO: Extension Services\nMFO: Advance Education Services\nMFO: Higher Education Services\nMFO: Research Services", activity:"Conduct GAD-related GM capability-building training/workshop/fora appropriate for GAD Thematic Areas: Advocacy & trainings / Gender in environment / DRRM / GAD responsive", responsible:"GAD Office, HRDO, Research, OQAA, All colleges, GAD Office", remarks:"Done.",
    indicators:{text:"No. of training/workshop/seminars & personnel reached - 5 trainings/workshops/Learning & Development sessions", m:334, f:427},
    actualResult:{text:"36 trainings/workshops/seminars/Learning & Development sessions", m:2090, f:3517},
    items:[
      ["Venue",334200,334200,"GAA"],
      ["Token",25000,81500,"GAA"],
      ["Seminar Package/Meals & Snacks/Fuel/Vehicle Rental/Honorarium",3500000,2387340,"GAA"],
      ["Supplies & Materials/Printing of Certificates",418455,418455,"GAA"],
      ["Honorarium/Professional Fee/Monetary Incentives",1560324.59,1560324.59,"GAA"],
      ["PS Attribution",6147560.37,6147560.37,"GAA"]
    ]},

  { issue:"Limited application of GAD Mainstreaming (GM) in Instruction, Research, Extension and Production / Magna Carta of Women (RA 9710), CHED Memo 2015-1", cause:"Partial integration of gender perspective in research and extension activities", result:"To fully integrate gender perspective in higher education research as part of a gender-responsive research program", org:"MFO: Technical Advisory Extension Program\nMFO: Research Program", activity:"Conduct gender-responsive research through use of Gender Analysis and Harmonized GAD Guidelines for Researchers", responsible:"Research and Extension Sector, GAD Office, ISRD, various offices/colleges in the University/external campus", remarks:"Done.",
    indicators:{text:"No. of GAD-related research and research learning session/write-shop conducted by December 2025 - 1 research with gender perspective and 1 learning session/write-shop", m:'', f:''},
    actualResult:{text:"1 research with GAD perspective completed and 1 write-shop conducted", m:'', f:''},
    items:[
      ["Research / 1 research learning session/write shop",220000,19000,"GAA"],
      ["Venue",5000,5000,"GAA"],
      ["Supplies/Materials/Printing of Certificates",5000,5000,"GAA"],
      ["PS Attribution",4273786.18,4273786.18,"GAA"]
    ]},

  { issue:"Productivity of employees affected due to filial obligations, affecting promotion of women to higher positions or participation in capability enhancement sessions / Magna Carta of Women IRR Section 37", cause:"Inadequate support services for personnel/students with young children and breastfeeding mothers (non-implementation of RA 10028)", result:"Inadequate support services to personnel and students with children", org:"MFO: Higher Education Program\nMFO: Technical Advisory Extension Program\nMFO: Advance Education Program\nMFO: Research Program", activity:"Establishment/maintenance of breastfeeding stations established in preceding years", responsible:"GAD Office, PMO, SPMO, various colleges, external campuses", remarks:"Done.",
    indicators:{text:"Fully maintained lactation rooms - new room at CIS and CPAG; 6 fully maintained lactation rooms at BSU La Trinidad (Admin Bldg, CHK, CA, CTE, CHET), Bokod Campus and Buguias Campus", m:'', f:''},
    actualResult:{text:"2 New Lactation Centers established (CIS and CPAG); 6 Lactation Centers maintained (CHK, CTE, CHET, Admin, Bokod, Buguias Campuses)", m:'', f:''},
    items:[
      ["PS Attribution",170000,1326525.74,"GAA"],
      ["Materials and Equipment of Lactation Room",350000,27350,"GAA"]
    ]},

  { issue:"Productivity of employees affected due to filial obligations, affecting promotion of women to higher positions or participation in capability enhancement sessions / Magna Carta of Women IRR Section 37", cause:"Productivity of employees affected due to filial obligations, affecting promotion of women to higher positions or participation in capability enhancement sessions", result:"Inadequate support services to personnel and students with children", org:"MFO: Technical Advisory Extension Program\nMFO: Higher Education Program\nMFO: Research Program\nMFO: Advance Education Program", activity:"Operationalize BSU CN Reproductive Health Care Center", responsible:"GAD Office, CN", remarks:"Done.",
    indicators:{text:"No. of maintained Reproductive Health Care Centers - 1 maintained BSU CN Reproductive Health Care Center", m:'', f:''},
    actualResult:{text:"1 BSU-CN Reproductive Healthcare Center maintained", m:'', f:''},
    items:[
      ["Supplies & Materials / Meals & Snacks / TEV",50000,27350,"GAA"],
      ["PS Attribution",322017.37,322017.37,"GAA"]
    ]},

  { issue:"Executive Order No. 340 s. 1997 directing NGAs and GOCCs to provide Day Care Services for employees' children under 5 years of age", cause:"Problems of parents and students related to child care", result:"Ensure personnel and students have access to agency child-care services to avoid absenteeism", org:"MFO: Higher Education Program\nMFO: Technical Advisory Extension Program\nMFO: Advance Education Program\nMFO: Research Program", activity:"Maintenance of Child Minding Center for working parents to ensure a safe place to leave their child while at work", responsible:"GAD Office, PMO, SPMO, CA, CHET, External Campus", remarks:"Done.",
    indicators:{text:"No. of established child minding centers - 1 established (CHET) and maintenance of centers at College of Agriculture (La Trinidad), Bokod Campus and Buguias Campus", m:'', f:''},
    actualResult:{text:"1 New Childminding Center established (International Dorm); 4 Childminding Centers maintained (CA, CTE, Bokod, Buguias Campuses)", m:'', f:''},
    items:[
      ["PS Attribution",100000,954056.42,"GAA"],
      ["Child Minding Center Materials, Equipment and Supplies",230000,226840,"GAA"]
    ]},

  { issue:"Low GAD component in Monitoring and Evaluation of PAPs in BSU / Magna Carta of Women (RA 9710)", cause:"Low integration of gender mainstreaming in BSU", result:"To strengthen GAD integration in the operations of BSU", org:"MFO: Higher Education Program\nMFO: Advance Education Program\nMFO: Research Program\nMFO: Technical Advisory Extension Program", activity:"Create a Monitoring Team to conduct monitoring and evaluation of the utilization/outcome of GAD PAPs and ensure effectiveness", responsible:"GAD Office / various offices/colleges in the university/external campus", remarks:"Done.",
    indicators:{text:"No. of monitoring and assessment meetings with reports - 4 meetings with reports conducted", m:'', f:''},
    actualResult:{text:"4 Monitoring and assessment meetings conducted", m:'', f:''},
    items:[
      ["Supplies and Materials",10000,0,"GAA"],
      ["Meals & Snacks",70000,0,"GAA"],
      ["PS Attribution",320000,660389.21,"GAA"]
    ]},

  { issue:"Magna Carta of Women IRR Section 37-C: Creation and/or Strengthening of the GAD Focal Points (GFPs)", cause:"Low capacity of GFPS members to develop and implement GAD programs and activities due to new members", result:"Capacitated GFPS members to implement GAD PAPs and advance GAD Mainstreaming (GM) in the University", org:"MFO: Advance Education Program\nMFO: Research Program\nMFO: Technical Advisory Extension Program\nMFO: Higher Education Program", activity:"Capability building/training for GFPS/Secretariat: GMEF/HGDG/GPB/GAD Agenda/GAD Deepening Session and other related trainings (Regional/National GAD forums/workshops)", responsible:"GAD Office, HRDO", remarks:"Done.",
    indicators:{text:"No. of trainings/seminars/workshop attendance for GFPS-members on GAD updates and mandates - 1 attendance per member", m:20, f:30},
    actualResult:{text:"2 Trainings/Workshops conducted for GFPS members; 1 Regional GFPS Assembly attended", m:24, f:38},
    items:[
      ["Token",12000,1000,"GAA"],
      ["Training/Seminar/Workshop",500000,233770,"GAA"],
      ["Professional Fee",99363.77,99363.77,"GAA"],
      ["Supplies/Materials/Printing of Certificates",25000,5000,"GAA"],
      ["Venue",5000,10000,"GAA"],
      ["Transportation",10000,0,"GAA"],
      ["Registration fee",9000,0,"GAA"],
      ["PS Attribution",481397.19,481397.19,"GAA"]
    ]},

  { issue:"Section 37-C, Rule VI of the Magna Carta of Women's IRR on duties and function of the GAD Focal Point System / Magna Carta of Women (RA 9710)", cause:"Compliance to provisions regarding regular monitoring of gender mainstreaming efforts", result:"To ensure operations of the GAD Office and monitor/evaluate GM efforts of the University", org:"MFO: Higher Education Program\nMFO: Advance Education Program\nMFO: Research Program\nMFO: Technical Advisory Extension Program", activity:"Regular coordination and meetings of GAD-GFPS (Execom, GFPS-TWG and external campus TWG members) and emergency meetings when necessary", responsible:"GAD Office", remarks:"Done.",
    indicators:{text:"No. of reports on regular meetings per campus - 6 reports conducted by year end; RGADC quarterly meetings", m:'', f:''},
    actualResult:{text:"22 Regular and Special GFPS-TWG Meetings; 3 RGADC-CAR Meetings attended", m:114, f:235},
    items:[
      ["Meals & Snack",110000,89010,"GAA"],
      ["PS Attribution (GFPS-TWG members)",450000,806971.61,"GAA"],
      ["Venue",28000,28000,"GAA"]
    ]},

  { issue:"Duties and function of the GAD Focal Point System / CHED Memo 2015-1", cause:"No plantilla personnel assigned to plan, implement and monitor GAD PAPs on a full-time basis", result:"To ensure operations of the GAD Office and monitor/evaluate GM efforts of the University", org:"MFO: Advance Education Program\nMFO: Research Program\nMFO: Technical Advisory Extension Program\nMFO: Higher Education Program", activity:"Engage support staff to assist in implementing GFPS PPAs and Gender Mainstreaming through rehiring of GAD staff and student assistants", responsible:"HRMO, OSS, CBOO", remarks:"Done.",
    indicators:{text:"Salary of casual GAD staff; no. of rehired personnel and student assistants - at least 2 staff renewed/rehired and 1 Student Assistant/SPES per semester", m:'', f:''},
    actualResult:{text:"2 Casual GAD staff renewed", m:'', f:''},
    items:[
      ["Student Assistant/SPES",100000,0,"GAA"],
      ["Salary of GAD Staff (Casual)",609923.12,824786.51,"GAA"]
    ]},

  { issue:"Low level of awareness on Gender Mainstreaming (GM) in Instruction, Research, Extension and Production among newly hired personnel / Magna Carta of Women (RA 9710), CHED Memo 2015-1", cause:"Low awareness among personnel; need to reinforce GAD concepts to employees", result:"To increase awareness of personnel and reinforce GAD concepts to employees", org:"MFO: Research Program\nMFO: Advance Education Program\nMFO: Higher Education Program\nMFO: Technical Advisory Extension Program", activity:"Conduct Gender Sensitivity Training (GST) for newly hired personnel (continuing activity)", responsible:"GAD Office, HRDO", remarks:"Done.",
    indicators:{text:"No. of trainings conducted for newly hired personnel - 1 training for at least 100% of newly hired personnel in 2025", m:'', f:''},
    actualResult:{text:"3 trainings conducted for 100% of newly hired personnel in 2025", m:'', f:''},
    items:[
      ["Meals & Snacks",42000,235300,"GAA"],
      ["Token",5000,0,"GAA"],
      ["Materials and Supplies",6000,6000,"GAA"],
      ["PS Attribution",934994,934994,"GAA"],
      ["Professional Fee/Honoraria",8647.46,8647.46,"GAA"],
      ["Venue",40000,0,"GAA"]
    ]},

  { issue:"Part V, Rule II, Section 4 of CHED Memorandum Order No. 1 Series of 2015 on Library and Learning Materials / CHED Memo 2015-1", cause:"Limited number of GAD library and related learning materials across various disciplines", result:"To increase provision of adequate and accessible library and related learning materials across disciplines and educational levels", org:"MFO: Higher Education Program\nMFO: Advance Education Program\nMFO: Research Program\nMFO: Technical Advisory Extension Program", activity:"Provision of knowledge products (books, magazines, multi-media) for adequate and accessible library and learning materials in support of gender-responsive curriculum programs", responsible:"ULIS, PMO, GAD Office, SPMO", remarks:"Done.",
    indicators:{text:"No. of procured library and learning materials - 20 books and 5 instructional materials", m:'', f:''},
    actualResult:{text:"667 books and instructional materials acquired for 2025", m:'', f:''},
    items:[
      ["Books and Instructional Materials",700000,3696044,"GAA"],
      ["PS Attribution",100000,657424.96,"GAA"]
    ]},

  { issue:"Presence of Gender Based Violence (GBV) issues/reports/cases in the university / Magna Carta of Women IRR Section 37", cause:"Limited extent and reach to target sectors in disseminating information and awareness", result:"Institutionalize GAD mechanisms in the University and sustain awareness campaigns on sexual harassment and gender-based violence", org:"MFO: Higher Education Program\nMFO: Advance Education Program\nMFO: Technical Advisory Extension Program\nMFO: Research Program", activity:"Conduct Seminar on Anti-Violence Against Women and Safe Spaces Act (Bawal Bastos Law) with related activities to mitigate GBV concerns", responsible:"GAD Office, UPAO, various offices/colleges in the University/external campus, PMO, SPMO", remarks:"Done.",
    indicators:{text:"No. of IEC materials/knowledge products; Shamag Official Publication with GAD corner maintained; at least 2 sector-specific knowledge products and 8 GAD Bulletin Boards maintained", m:'', f:''},
    actualResult:{text:"1 Shamag Official Publication with GAD corner accessible/downloadable via official website; 8 GAD Bulletin Boards maintained", m:'', f:''},
    items:[
      ["Token",10000,0,"GAA"],
      ["PS TWG Members",100000,127343.48,"GAA"],
      ["Supplies and Materials",10000,10000,"GAA"],
      ["Shamag",220000,96000,"GAA"]
    ]},

  { issue:"Part V of CMO 01, s.2015 on establishment of Gender-Responsive Curricular Programs / CHED Memo 2015-1", cause:"Limited integration of gender-responsive instruction and curriculum development", result:"Integration of gender mainstreaming in curriculum/subjects at all levels", org:"MFO: Advanced Education Program\nMFO: Higher Education Program", activity:"Preparation of syllabi and classroom teaching integrating gender perspective", responsible:"GAD Office, All colleges in the University and external campus", remarks:"Done.",
    indicators:{text:"No. of faculty members integrating gender perspective in syllabi - 619 faculty integrating and developing gender-sensitive learning materials", m:300, f:319},
    actualResult:{text:"511 faculty and COS employees integrated and developed gender-sensitive learning materials", m:263, f:248},
    items:[
      ["PS",41723000.76,77287717.71,"GAA"]
    ]},

  { issue:"Magna Carta of Women IRR Section 37: Gender Mainstreaming as a Strategy for Implementing the GAD Office, Section 17", cause:"Need to enhance the organizational structure of the GAD Office", result:"To ensure implementation and relevance of Gender Mainstreaming through sustained annual GAD-PAPs and a GAD Office that coordinates queries and concerns", org:"MFO: Higher Education Program\nMFO: Technical Advisory Extension Program\nMFO: Advance Education Program\nMFO: Research Program", activity:"Maintain the GAD Office per campus as well as the University GAD Office", responsible:"GAD Office, PMO, SPMO, various offices/colleges in the University/External Campuses", remarks:"Done.",
    indicators:{text:"Fully maintain GAD Offices - maintain 3 GAD Offices", m:'', f:''},
    actualResult:{text:"3 GAD Offices maintained (La Trinidad, Bokod and Buguias Campus)", m:'', f:''},
    items:[
      ["Supplies, Equipment and Materials",161316.97,582232,"GAA"],
      ["PS on Procurement of Supplies and Materials",30992.09,839289.83,"GAA"],
      ["PS of GAD Director (40%)",468729.17,540442.46,"GAA"],
      ["PS of Buguias Focal Person",462760.67,462760.67,"GAA"],
      ["PS of Bokod Focal Person",457878.12,457878.12,"GAA"]
    ]},

  { issue:"Institutionalizing GAD Database and Sex-Disaggregated Database / Magna Carta of Women (RA 9710), Section 36", cause:"Minimal awareness and appreciation of the relevance of a centralized Sex-Disaggregated database", result:"To establish a centralized GAD-related database of the University", org:"MFO: Higher Education Program\nMFO: Advance Education Program\nMFO: Research Program\nMFO: Technical Advisory Extension Program", activity:"Continuous data gathering and consolidation of Sex-Disaggregated Data (SDD) for a centralized GAD database for gender analysis and report preparation; training-workshop on gender-based database", responsible:"ICT Office, GAD Office, HRMO, OUR", remarks:"Done.",
    indicators:{text:"No. of well-organized and maintained GAD databases - 1 GAD database established per college/unit", m:'', f:''},
    actualResult:{text:"1 maintained GAD Database", m:'', f:''},
    items:[
      ["PS: HRMO/OUR/ICT TWG",178800.63,259614.87,"GAA"],
      ["Maintenance of SDD/Internet connection",1000000,1000000,"GAA"],
      ["Seminar Package/Meals & Snacks/Fuel/Vehicle Rental/Honorarium",330000,0,"GAA"]
    ]},

  { issue:"Disaster assistance to affected students not gender-responsive / Magna Carta for Women, Chapter IV Section 10 and RA 10121, Section 2 & 9", cause:"Limited resources of DSWD and LGU to provide for transient student residents; limited appreciation of women's role in nation building among employees and students", result:"To ensure disaster assistance provided to distressed students is gender-responsive", org:"MFO: Advance Education Program\nMFO: Research Program\nMFO: Technical Advisory Extension Program\nMFO: Higher Education Program", activity:"Provision of gender-responsive services to employees/students who experienced crisis/disaster (e.g. distribution of hygiene kits for both women and men)", responsible:"GAD Office, HDRO, NSTP, various offices/colleges in the University", remarks:"Done. No disaster occurred in the University in 2025, so there was no provision for disaster assistance.",
    indicators:{text:"No. of most-affected employees/students served during crises - 1,000 pax", m:'', f:''},
    actualResult:{text:"0", m:'', f:''},
    items:[
      ["PS TWG Members",77882.12,0,"GAA"],
      ["Crisis pack: ₱200/pack x 1,000 pax",200000,0,"GAA"]
    ]},

  { issue:"Compliance to Proclamation 227 on the observance of Women's Role in History Month and Proclamation 1172, s. 2006 on the 18-Day Campaign to End Violence Against Women (VAW)", cause:"Need to highlight women's rights and role in national/nation building; need to invoke protection against VAW, gender-based violence, Safe Spaces Act (RA 11313)", result:"To strengthen awareness of BSU students/employees on women's rights and their role in national development and nation building", org:"MFO: Higher Education Program\nMFO: Advance Education Program\nMFO: Research Program\nMFO: Technical Advisory Extension Program", activity:"Participation in the 18-Day Campaign to End VAW and Women's Month Celebration activities/programs organized by PCW and other agencies", responsible:"GAD Office, various offices/colleges in the University/external campus", remarks:"Done.",
    indicators:{text:"No. of activities conducted per campus - at least 1 activity per campus", m:'', f:''},
    actualResult:{text:"10 Activities conducted across all campuses for the 2025 18-Day Campaign to End VAW and National Women's Month Celebration", m:401, f:823},
    items:[
      ["Advocacy Campaign Costs/Capacity Development",200000,156510,"GAA"],
      ["Transportation",5000,5000,"GAA"],
      ["Venue",34000,34000,"GAA"],
      ["PS Attribution",894518.54,894518.54,"GAA"],
      ["Token",10000,10000,"GAA"],
      ["Supplies/Materials/Printing of Certificates",59000,59000,"GAA"]
    ]},

  { issue:"Compliance to Section 18 of MCW RA 9710 Special Leave Benefits for Women, RA 8187 Paternity Leave Act of 1996, Section 8 of RA 8972 Solo Parents Welfare Act of 2000, Section 43 of RA 9262", cause:"Employees may require special leaves due to parental obligations, health concerns and other circumstances", result:"Enhanced support services for employees in need of special leaves", org:"MFO: Research Program\nMFO: Advance Education Program\nMFO: Higher Education Program\nMFO: Technical Advisory Extension Program", activity:"Provision of gender leaves and conduct of Seminar on Gender-Related Leaves for newly hired employees", responsible:"HRMO, CBOO, various offices/colleges in the University", remarks:"Done.",
    indicators:{text:"No. of Maternity, Paternity, Solo Parent, gynecological, VAWC leaves availed and 1 seminar conducted - 100% provision", m:20, f:50},
    actualResult:{text:"100% provision of gender leaves to BSU employees", m:7, f:24},
    items:[
      ["PS Attribution",500000,2367412,"GAA"],
      ["Seminar Package/Meals & Snacks/Honorarium",50000,0,"GAA"]
    ]},

  { issue:"Lack of necessary skills and knowledge on gender-related concerns and needs in emergency/disaster preparedness / Magna Carta for Women, Chapter IV Section 10 and RA 10121, Section 2 & 9", cause:"Old conventional practice during past disasters; no clear roles for women in disaster management", result:"Increase knowledge and skills on gender-related concerns necessary to prepare for and respond to disasters/emergencies, with equal roles for men and women", org:"MFO: Higher Education Program\nMFO: Advance Education Program\nMFO: Research Program\nMFO: Technical Advisory Extension Program", activity:"Conduct training to address gender issues and concerns that might arise during health emergencies and disaster situations", responsible:"GAD Office, HDRO, NSTP, SSO, various offices/colleges in the University", remarks:"Done.",
    indicators:{text:"No. of DRRM trainings conducted per campus - at least 2 DRRM trainings", m:'', f:''},
    actualResult:{text:"2 DRRM trainings conducted", m:57, f:43},
    items:[
      ["Token: 70 pax x ₱1,000",70000,12970,"GAA"],
      ["DRRM Training",500000,94900,"GAA"],
      ["PS Attribution",77882.12,52006.92,"GAA"]
    ]},

  { section:"ATTRIBUTED PROGRAM", issue:"", cause:"", result:"", org:"", activity:"Construction of College of Arts and Humanities Building — Project Cost: ₱25,000,000.00 · HGDG Score: 12.81 · Attributed Amount: ₱16,202,500.00", responsible:"GAD Office, PMO, SPMO, various offices/colleges in the University/external campus", remarks:"On-going",
    indicators:{text:"", m:'', f:''},
    actualResult:{text:"", m:'', f:''},
    items:[
      ["CO",16202500,0,"GAA"]
    ]},
];

// ---------------------------------------------------------------------
// Rendering
// ---------------------------------------------------------------------
const tbody = document.getElementById('table-body');
let rowNum = 0;

function fmt(n){
  n = isFinite(n) ? n : 0;
  return n.toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
}
function fmtInt(n){
  n = isFinite(n) ? n : 0;
  return n.toLocaleString('en-US');
}
function parseMoney(s){
  if(s == null) return 0;
  const cleaned = String(s).replace(/[₱,\s]/g,'');
  const v = parseFloat(cleaned);
  return isFinite(v) ? v : 0;
}

function makeCell(colName, content){
  const td = document.createElement('td');
  td.setAttribute('data-col', colName);
  td.setAttribute('contenteditable','true');
  td.textContent = content || '';
  return td;
}

function buildItemsEditor(items){
  const wrap = document.createElement('div');
  wrap.className = 'items-editor';
  const list = document.createElement('div');
  list.className = 'items-list';
  wrap.appendChild(list);

  function addItemRow(item){
    const line = document.createElement('div');
    line.className = 'item-line';

    const labelInp = document.createElement('input');
    labelInp.type = 'text';
    labelInp.className = 'item-label';
    labelInp.placeholder = 'Line item description';
    labelInp.value = item.label || '';

    const metaRow = document.createElement('div');
    metaRow.className = 'item-meta-row';

    const amtWrap = document.createElement('div');
    amtWrap.className = 'item-amt-wrap';
    const peso = document.createElement('span');
    peso.className = 'peso';
    peso.textContent = '₱';
    const amtInp = document.createElement('input');
    amtInp.type = 'number';
    amtInp.step = '0.01';
    amtInp.className = 'item-amt';
    amtInp.value = item.amount || 0;
    amtWrap.append(peso, amtInp);

    const srcInp = document.createElement('input');
    srcInp.type = 'text';
    srcInp.className = 'item-src';
    srcInp.value = item.source || 'GAA';
    srcInp.title = 'Fund source';

    const rm = document.createElement('button');
    rm.type = 'button';
    rm.className = 'item-rm';
    rm.textContent = '×';
    rm.title = 'Remove line item';
    rm.onclick = () => { line.remove(); recalcAll(); };

    metaRow.append(amtWrap, srcInp, rm);
    line.append(labelInp, metaRow);
    list.appendChild(line);
  }

  items.forEach(addItemRow);

  const addBtn = document.createElement('button');
  addBtn.type = 'button';
  addBtn.className = 'item-add';
  addBtn.textContent = '+ line item';
  addBtn.onclick = () => { addItemRow({label:'',amount:0,source:'GAA'}); recalcAll(); };

  const subtotal = document.createElement('div');
  subtotal.className = 'item-subtotal';
  subtotal.textContent = 'Subtotal: ₱0.00';

  wrap.append(addBtn, subtotal);
  return wrap;
}

function buildGenderCell(data){
  data = data || {text:'', m:'', f:''};
  const wrap = document.createElement('div');
  wrap.className = 'gender-cell';

  const txt = document.createElement('div');
  txt.className = 'gc-text';
  txt.contentEditable = 'true';
  txt.textContent = data.text || '';

  const genderRow = document.createElement('div');
  genderRow.className = 'gc-gender-row';

  const mField = document.createElement('div');
  mField.className = 'gc-field';
  const mLabel = document.createElement('label'); mLabel.textContent = 'M';
  const mInp = document.createElement('input');
  mInp.type = 'number'; mInp.className = 'gc-amt gc-m'; mInp.placeholder = '—';
  mInp.value = (data.m === '' || data.m == null) ? '' : data.m;
  mField.append(mLabel, mInp);

  const fField = document.createElement('div');
  fField.className = 'gc-field';
  const fLabel = document.createElement('label'); fLabel.textContent = 'F';
  const fInp = document.createElement('input');
  fInp.type = 'number'; fInp.className = 'gc-amt gc-f'; fInp.placeholder = '—';
  fInp.value = (data.f === '' || data.f == null) ? '' : data.f;
  fField.append(fLabel, fInp);

  const total = document.createElement('div');
  total.className = 'gc-total';
  total.textContent = 'Total: —';

  genderRow.append(mField, fField, total);
  wrap.append(txt, genderRow);
  return wrap;
}

function addSectionRow(label){
  const tr = document.createElement('tr');
  tr.className = 'section-row';
  const td = document.createElement('td');
  td.colSpan = 13;
  td.textContent = label;
  tr.appendChild(td);
  tbody.appendChild(tr);
}

function addDataRow(data){
  rowNum++;
  const tr = document.createElement('tr');
  tr.dataset.rowId = rowNum;

  const tdNum = document.createElement('td');
  tdNum.className = 'col-num';
  tdNum.textContent = rowNum;
  tr.appendChild(tdNum);

  tr.appendChild(makeCell('issue', data.issue));
  tr.appendChild(makeCell('cause', data.cause));
  tr.appendChild(makeCell('result', data.result));
  tr.appendChild(makeCell('org', data.org));
  tr.appendChild(makeCell('activity', data.activity));

  const tdIndicators = document.createElement('td');
  tdIndicators.setAttribute('data-col','indicators');
  tdIndicators.appendChild(buildGenderCell(data.indicators));
  tr.appendChild(tdIndicators);

  const tdActual = document.createElement('td');
  tdActual.setAttribute('data-col','actualResult');
  tdActual.appendChild(buildGenderCell(data.actualResult));
  const compareBadge = document.createElement('div');
  compareBadge.className = 'gc-compare';
  compareBadge.style.display = 'none';
  tdActual.querySelector('.gender-cell').appendChild(compareBadge);
  tr.appendChild(tdActual);

  const budgetItems = (data.items || []).map(it => ({label:it[0], amount:it[1], source:it[3] || 'GAA'}));
  const costItems    = (data.items || []).map(it => ({label:it[0], amount:0, source:it[3] || 'GAA'}));

  const tdBudget = document.createElement('td');
  tdBudget.setAttribute('data-col','budget');
  tdBudget.appendChild(buildItemsEditor(budgetItems));
  tr.appendChild(tdBudget);

  const tdCost = document.createElement('td');
  tdCost.setAttribute('data-col','cost');
  tdCost.appendChild(buildItemsEditor(costItems));
  const varianceBadge = document.createElement('div');
  varianceBadge.className = 'variance-badge neutral';
  varianceBadge.textContent = 'On budget';
  tdCost.querySelector('.items-editor').appendChild(varianceBadge);
  tr.appendChild(tdCost);

  tr.appendChild(makeCell('responsible', data.responsible));
  tr.appendChild(makeCell('remarks', data.remarks));

  const tdAct = document.createElement('td');
  tdAct.className = 'col-actions';
  const btn = document.createElement('button');
  btn.className = 'remove-btn';
  btn.textContent = '×';
  btn.title = 'Remove row';
  btn.onclick = async () => { 
    if (data.id && !String(data.id).startsWith('i_')) {
      try { await api.delete(`/gpb/item/${data.id}`); } 
      catch (e) { console.error('Failed to delete from DB', e); }
    }
    tr.remove(); 
    renumber(); 
    recalcAll(); 
  };
  tdAct.appendChild(btn);
  tr.appendChild(tdAct);

  tbody.appendChild(tr);
}

function addRowButton(afterSection){
  const tr = document.createElement('tr');
  tr.className = 'add-row';
  const td = document.createElement('td');
  td.colSpan = 13;
  const btn = document.createElement('button');
  btn.textContent = '+ Add row to ' + afterSection;
  btn.onclick = () => {
    rowNum++;
    const newTr = document.createElement('tr');
    newTr.dataset.rowId = rowNum;
    const tdNum = document.createElement('td');
    tdNum.className = 'col-num'; tdNum.textContent = rowNum;
    newTr.appendChild(tdNum);
    ['issue','cause','result','org','activity'].forEach(c=>{
      newTr.appendChild(makeCell(c, ''));
    });

    const tdI = document.createElement('td'); tdI.setAttribute('data-col','indicators');
    tdI.appendChild(buildGenderCell({text:'',m:'',f:''}));
    newTr.appendChild(tdI);

    const tdA = document.createElement('td'); tdA.setAttribute('data-col','actualResult');
    tdA.appendChild(buildGenderCell({text:'',m:'',f:''}));
    const cb = document.createElement('div'); cb.className = 'gc-compare'; cb.style.display = 'none';
    tdA.querySelector('.gender-cell').appendChild(cb);
    newTr.appendChild(tdA);

    const tdB = document.createElement('td'); tdB.setAttribute('data-col','budget');
    tdB.appendChild(buildItemsEditor([{label:'',amount:0,source:'GAA'}]));
    newTr.appendChild(tdB);
    const tdC = document.createElement('td'); tdC.setAttribute('data-col','cost');
    tdC.appendChild(buildItemsEditor([{label:'',amount:0,source:'GAA'}]));
    const vb = document.createElement('div'); vb.className='variance-badge neutral'; vb.textContent='On budget';
    tdC.querySelector('.items-editor').appendChild(vb);
    newTr.appendChild(tdC);

    ['responsible','remarks'].forEach(c=>{ newTr.appendChild(makeCell(c,'')); });
    const tdAct = document.createElement('td');
    tdAct.className = 'col-actions';
    const rbtn = document.createElement('button');
    rbtn.className = 'remove-btn'; rbtn.textContent = '×'; rbtn.title = 'Remove row';
    rbtn.onclick = () => { newTr.remove(); renumber(); recalcAll(); };
    tdAct.appendChild(rbtn);
    newTr.appendChild(tdAct);

    tr.parentNode.insertBefore(newTr, tr);
    renumber();
    recalcAll();
    newTr.querySelector('td[data-col="issue"]').focus();
  };
  td.appendChild(btn);
  tr.appendChild(td);
  tbody.appendChild(tr);
}

function renumber(){
  let n = 0;
  document.querySelectorAll('#table-body tr').forEach(tr=>{
    if(tr.classList.contains('section-row') || tr.classList.contains('add-row')) return;
    n++;
    tr.querySelector('td.col-num').textContent = n;
  });
  document.getElementById('row-count').textContent = n;
}

// ---------------------------------------------------------------------
// Autocalculations
// ---------------------------------------------------------------------
function recalcAll(){
  let grandBudget = 0, grandCost = 0;
  let grandTargetM = 0, grandTargetF = 0, grandActualM = 0, grandActualF = 0;

  document.querySelectorAll('#table-body tr[data-row-id]').forEach(tr=>{
    // --- money ---
    const bTd = tr.querySelector('td[data-col="budget"]');
    const cTd = tr.querySelector('td[data-col="cost"]');
    if(bTd && cTd){
      let bSum = 0;
      bTd.querySelectorAll('.item-amt').forEach(i => bSum += parseFloat(i.value) || 0);
      let cSum = 0;
      cTd.querySelectorAll('.item-amt').forEach(i => cSum += parseFloat(i.value) || 0);

      bTd.querySelector('.item-subtotal').textContent = 'Subtotal: ₱' + fmt(bSum);
      cTd.querySelector('.item-subtotal').textContent = 'Subtotal: ₱' + fmt(cSum);

      const variance = cSum - bSum;
      const vBadge = cTd.querySelector('.variance-badge');
      if(vBadge){
        if(Math.abs(variance) < 0.005){
          vBadge.textContent = 'On budget';
          vBadge.className = 'variance-badge neutral';
        } else if(variance > 0){
          vBadge.textContent = 'Over by ₱' + fmt(variance);
          vBadge.className = 'variance-badge over';
        } else {
          vBadge.textContent = 'Under by ₱' + fmt(Math.abs(variance));
          vBadge.className = 'variance-badge under';
        }
      }
      grandBudget += bSum;
      grandCost += cSum;
    }

    // --- gender headcounts ---
    const iTd = tr.querySelector('td[data-col="indicators"]');
    const aTd = tr.querySelector('td[data-col="actualResult"]');
    if(iTd && aTd){
      const iM = iTd.querySelector('.gc-m'), iF = iTd.querySelector('.gc-f');
      const aM = aTd.querySelector('.gc-m'), aF = aTd.querySelector('.gc-f');
      const iMv = iM.value === '' ? null : (parseFloat(iM.value) || 0);
      const iFv = iF.value === '' ? null : (parseFloat(iF.value) || 0);
      const aMv = aM.value === '' ? null : (parseFloat(aM.value) || 0);
      const aFv = aF.value === '' ? null : (parseFloat(aF.value) || 0);

      const iTotalEl = iTd.querySelector('.gc-total');
      const aTotalEl = aTd.querySelector('.gc-total');
      const iHasData = iMv !== null || iFv !== null;
      const aHasData = aMv !== null || aFv !== null;
      iTotalEl.textContent = iHasData ? ('Total: ' + fmtInt((iMv||0)+(iFv||0))) : 'Total: —';
      aTotalEl.textContent = aHasData ? ('Total: ' + fmtInt((aMv||0)+(aFv||0))) : 'Total: —';

      const compareBadge = aTd.querySelector('.gc-compare');
      if(compareBadge){
        if(iHasData){
          const targetTotal = (iMv||0)+(iFv||0);
          const actualTotal = (aMv||0)+(aFv||0);
          const delta = actualTotal - targetTotal;
          compareBadge.style.display = 'inline-block';
          if(delta >= 0){
            compareBadge.textContent = 'vs target (' + fmtInt(targetTotal) + '): +' + fmtInt(delta);
            compareBadge.className = 'gc-compare positive';
          } else {
            compareBadge.textContent = 'vs target (' + fmtInt(targetTotal) + '): ' + fmtInt(delta);
            compareBadge.className = 'gc-compare negative';
          }
        } else {
          compareBadge.style.display = 'none';
        }
      }

      grandTargetM += iMv || 0;
      grandTargetF += iFv || 0;
      grandActualM += aMv || 0;
      grandActualF += aFv || 0;
    }
  });

  document.getElementById('computed-budget-total').textContent = '₱' + fmt(grandBudget);
  document.getElementById('computed-cost-total').textContent = '₱' + fmt(grandCost);
  syncSummaryFromTable();
  const grandVar = grandCost - grandBudget;
  const cvEl = document.getElementById('computed-variance-total');
  cvEl.textContent = (Math.abs(grandVar) < 0.005 ? 'On budget overall' : (grandVar > 0 ? 'Over by ₱' + fmt(grandVar) : 'Under by ₱' + fmt(Math.abs(grandVar))));

  

  document.getElementById('computed-target-total').textContent =
    'Target — M: ' + fmtInt(grandTargetM) + ' · F: ' + fmtInt(grandTargetF) + ' · Total: ' + fmtInt(grandTargetM + grandTargetF);
  document.getElementById('computed-actual-total').textContent =
    'Actual — M: ' + fmtInt(grandActualM) + ' · F: ' + fmtInt(grandActualF) + ' · Total: ' + fmtInt(grandActualM + grandActualF);
}

function recalcPercentages(){
  const totalBudget = parseMoney(document.getElementById('f-totalbudget').textContent);
  const actualGad = parseMoney(document.getElementById('f-actualgad').textContent);
  const origBudget = parseMoney(document.getElementById('f-origbudget').textContent);
  const util = origBudget ? (actualGad / origBudget * 100) : 0;
  const pctgad = totalBudget ? (actualGad / totalBudget * 100) : 0;
  document.getElementById('f-util').textContent = util.toFixed(2) + '%';
  document.getElementById('f-pctgad').textContent = pctgad.toFixed(2) + '%';
}

function syncSummaryFromTable(){
  const bText = document.getElementById('computed-budget-total').textContent;
  const cText = document.getElementById('computed-cost-total').textContent;
  document.getElementById('f-origbudget').textContent = bText;
  document.getElementById('f-actualgad').textContent = cText;
  recalcPercentages();
}

// Event delegation: any change to a line-item amount or gender count recalculates everything
tbody.addEventListener('input', (e)=>{
  if(e.target.classList.contains('item-amt') || e.target.classList.contains('gc-amt')) recalcAll();
});


['f-totalbudget','f-actualgad','f-origbudget'].forEach(id=>{
  document.getElementById(id).addEventListener('input', recalcPercentages);
});

recalcAll();
recalcPercentages();

// ---------------------------------------------------------------------
// Export helpers
// ---------------------------------------------------------------------
function syncInputAttrsForSnapshot(){
  document.querySelectorAll('input').forEach(el=>{
    el.setAttribute('value', el.value);
  });
}

function downloadSnapshot(){
  syncInputAttrsForSnapshot();
  const clone = document.documentElement.cloneNode(true);
  const blob = new Blob(['<!DOCTYPE html>\n' + clone.outerHTML], {type:'text/html'});
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = 'GAD-AR-2025-edited.html';
  document.body.appendChild(a);
  a.click();
  a.remove();
  URL.revokeObjectURL(url);
}

function csvEscape(s){
  if(s == null) s = '';
  s = String(s).replace(/"/g,'""');
  return '"' + s + '"';
}

function itemsToText(td){
  const lines = [];
  td.querySelectorAll('.item-line').forEach(line=>{
    const label = line.querySelector('.item-label').value;
    const amt = line.querySelector('.item-amt').value;
    const src = line.querySelector('.item-src').value;
    lines.push((label || '(untitled)') + ': ₱' + fmt(parseFloat(amt)||0) + ' (' + src + ')');
  });
  return lines.join(' | ');
}

function exportCSV(){
  const headers = ['#','Gender Issue/GAD Mandate','Cause of Gender Issue','GAD Result Statement/Objective','Relevant Org MFO/PAP/PPA','GAD Activity',
    'Performance Indicators/Targets (text)','Indicator Target - Male','Indicator Target - Female',
    'Actual Result (text)','Actual Result - Male','Actual Result - Female',
    'Total Agency Approved Budget (line items)','Budget Subtotal','Actual Cost/Expenditure (line items)','Actual Subtotal',
    'Responsible Unit/Office','Variance/Remarks'];
  const lines = [headers.map(csvEscape).join(',')];
  document.querySelectorAll('#table-body tr').forEach(tr=>{
    if(tr.classList.contains('section-row')){
      lines.push(csvEscape('--- ' + tr.textContent.trim() + ' ---'));
      return;
    }
    if(tr.classList.contains('add-row')) return;
    const num = tr.querySelector('td.col-num').textContent;
    const get = (col) => { const el = tr.querySelector('td[data-col="'+col+'"]'); return el ? el.textContent : ''; };
    const iTd = tr.querySelector('td[data-col="indicators"]');
    const aTd = tr.querySelector('td[data-col="actualResult"]');
    const bTd = tr.querySelector('td[data-col="budget"]');
    const cTd = tr.querySelector('td[data-col="cost"]');
    const bSubtotal = bTd.querySelector('.item-subtotal').textContent.replace('Subtotal: ','');
    const cSubtotal = cTd.querySelector('.item-subtotal').textContent.replace('Subtotal: ','');
    const vals = [
      num, get('issue'), get('cause'), get('result'), get('org'), get('activity'),
      iTd.querySelector('.gc-text').textContent, iTd.querySelector('.gc-m').value, iTd.querySelector('.gc-f').value,
      aTd.querySelector('.gc-text').textContent, aTd.querySelector('.gc-m').value, aTd.querySelector('.gc-f').value,
      itemsToText(bTd), bSubtotal, itemsToText(cTd), cSubtotal,
      get('responsible'), get('remarks')
    ];
    lines.push(vals.map(csvEscape).join(','));
  });
  const blob = new Blob([lines.join('\n')], {type:'text/csv'});
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = 'GAD-AR-2025.csv';
  document.body.appendChild(a);
  a.click();
  a.remove();
  URL.revokeObjectURL(url);
}

// keep masthead badge in sync with the editable Reference/Date fields



  
  const btnDownload = document.getElementById('btn-download');
  if(btnDownload) btnDownload.onclick = downloadSnapshot;
  const btnExport = document.getElementById('btn-export');
  if(btnExport) btnExport.onclick = exportCSV;
  const btnPrint = document.getElementById('btn-print');
  if(btnPrint) btnPrint.onclick = () => window.print();

  
  api.get('/plan').then(res => {
    if(res.data && res.data.org) {
      const org = res.data.org;
      if (document.getElementById('f-org')) document.getElementById('f-org').textContent = org.name || '';
      if (document.getElementById('f-category')) document.getElementById('f-category').textContent = org.category || '';
      if (document.getElementById('f-hierarchy')) document.getElementById('f-hierarchy').textContent = org.hierarchy || '';
      if (document.getElementById('f-totalbudget')) {
        document.getElementById('f-totalbudget').textContent = '₱' + parseFloat(org.totalOrgBudget || 0).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
      }
    }
    if(res.data && res.data.items) {
      let totalGadBudget = 0;
      const dynamicRows = res.data.items.map(item => {
        const indText = item.indicators || '';
        let m = '', f = '';
        const mMatch = indText.match(/(?:m(?:ale)?s?\b|men|boys?)\s*[:=\-]?\s*(\d+)|(\d+)\s*(?:m(?:ale)?s?\b|men|boys?)/i);
        if (mMatch) m = parseInt(mMatch[1] || mMatch[2]);
        const fMatch = indText.match(/(?:f(?:emale)?s?\b|women|girls?)\s*[:=\-]?\s*(\d+)|(\d+)\s*(?:f(?:emale)?s?\b|women|girls?)/i);
        if (fMatch) f = parseInt(fMatch[1] || fMatch[2]);
        
        return {
          id: item.id,
          section: item.section || 'General',
          issue: item.mandate || '',
          cause: item.cause || '',
          result: item.result || '',
          org: item.mfo || '',
          activity: item.activity || '',
          responsible: item.responsible || '',
          indicators: { text: indText, m: m, f: f },
          actualResult: { text: '', m: item.actualResult_m || '', f: item.actualResult_f || '' },
          items: (item.budgetLines || []).map(b => {
            const amt = parseFloat(b.amount) || 0;
            totalGadBudget += amt;
            return [
              b.label || '', 
              amt, 
              amt, // Pre-fill actual cost with budget
              b.source || 'GAA'
            ];
          })
        };
      });
      
      if (document.getElementById('f-origbudget')) {
        document.getElementById('f-origbudget').textContent = '₱' + totalGadBudget.toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
      }
      
      const order = { 'client': 1, 'org': 2, 'attributed': 3, 'General': 4 };
      dynamicRows.sort((a, b) => (order[a.section] || 99) - (order[b.section] || 99));

      const sectionLabels = {
        'client': 'CLIENT-FOCUSED ACTIVITIES',
        'org': 'ORGANIZATION-FOCUSED ACTIVITIES',
        'attributed': 'ATTRIBUTED PROGRAM'
      };

      let currentSection = null;
      dynamicRows.forEach((r) => {
        if(r.section && r.section !== currentSection){
          if(currentSection !== null){
            addRowButton(currentSection);
          }
          currentSection = r.section;
          addSectionRow(sectionLabels[currentSection] || currentSection);
        }
        addDataRow(r);
      });
      addRowButton(currentSection);
      renumber();
      
      recalcAll();
      recalcPercentages();
    }
  }).catch(err => {
    console.error('Failed to load plan data', err);
  });

});
</script>

<style>


.report-wrapper {
  
  & {
    --green-deep: #1a1a2e; /* Dark header/sidebar background */
    --green-mid: #2d1b4e; /* Dark purple gradient end */
    --gold: #9333ea; /* Vibrant purple accent (buttons, highlights) */
    --gold-soft: #c084fc; /* Lighter purple */
    --cream: #ffffff; /* System white backgrounds */
    --paper: #ffffff; /* Card/Panel backgrounds */
    --ink: #0f172a; /* Slate-900 main text */
    --ink-soft: #64748b; /* Slate-500 subtext */
    --line: #e2e8f0; /* Slate-200 borders */
    --sage: #f8fafc; /* Slate-50 alt backgrounds/hover */
    --danger: #ef4444; /* Red-500 */
    --danger-bg: #fee2e2; /* Red-100 */
    --ok: #22c55e; /* Green-500 */
    --ok-bg: #dcfce7; /* Green-100 */
}
  & * { box-sizing:border-box; }
  
  & { background: transparent !important; 
    background:var(--cream);
    color:var(--ink);
    font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;
    font-size:14px;
    line-height:1.5;
    padding:0 0 80px;
  }

  /* ---------- Masthead ---------- */
  .masthead{
    background:linear-gradient(160deg,var(--green-deep),var(--green-mid) 70%);
    color:var(--cream);
    padding:38px clamp(18px,4vw,56px) 30px;
    position:relative;
    overflow:hidden;
    border-bottom:5px solid var(--gold);
  }
  .masthead::after{
    content:"";
    position:absolute;
    inset:0;
    background-image:repeating-linear-gradient(135deg, rgba(255,255,255,0.035) 0 2px, transparent 2px 26px);
    pointer-events:none;
  }
  .masthead-top{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:20px;
    flex-wrap:wrap;
    position:relative;
    z-index:1;
  }
  .eyebrow{
    font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;
    font-size:11px;
    letter-spacing:.14em;
    text-transform:uppercase;
    color:var(--gold-soft);
    margin:0 0 10px;
  }
  h1{
    font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;
    font-weight:600;
    font-size:clamp(28px,4vw,42px);
    margin:0 0 6px;
    letter-spacing:-.01em;
  }
  .org-line{
    font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;
    font-style:italic;
    font-weight:500;
    font-size:17px;
    color:var(--gold-soft);
  }
  .badge{
    font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;
    font-size:11px;
    text-align:right;
    color:rgba(250,247,240,.75);
    line-height:1.7;
  }
  .badge b{color:var(--cream);font-weight:600;}

  /* ---------- Info panel ---------- */
  .info-wrap{
    padding:0 clamp(18px,4vw,56px);
    margin-top:-22px;
    position:relative;
    z-index:2;
  }
  .info-panel{
    background:var(--paper);
    border:1px solid var(--line);
    border-radius:10px;
    box-shadow:0 14px 30px -18px rgba(30,69,54,.35);
    padding:22px clamp(14px,3vw,28px);
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
    gap:18px 26px;
  }
  .field label{
    display:flex;
    align-items:center;
    gap:6px;
    font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;
    font-size: 12.5px;
    letter-spacing:.08em;
    text-transform:uppercase;
    color:var(--ink-soft);
    margin-bottom:5px;
  }
  .auto-tag{
    font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;
    font-size:8.5px;
    letter-spacing:.04em;
    background:var(--sage);
    color:var(--gold);
    padding:1px 5px;
    border-radius:4px;
    text-transform:none;
  }
  .field .val{
    font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;
    font-weight:600;
    font-size:16px;
    padding:5px 8px;
    border-radius:5px;
    border:1px solid transparent;
    outline:none;
    min-height:1.4em;
  }
  .field .val:hover{border-color:var(--line);}
  .field .val:focus{border-color:var(--gold);background:var(--sage);}
  .field.stat .val{font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;color:var(--gold);}
  .field.computed .val{background:var(--sage);color:var(--gold);font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;cursor:default;}

  /* ---------- Toolbar ---------- */
  .toolbar{
    padding:22px clamp(18px,4vw,56px) 0;
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    align-items:center;
    justify-content:space-between;
  }
  .toolbar .left-group, .toolbar .right-group{display:flex;gap:8px;flex-wrap:wrap;align-items:center;}
  button{
    font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;
    font-size: 14.5px;
    font-weight:600;
    letter-spacing:.01em;
    padding:9px 14px;
    border-radius:7px;
    border:1px solid var(--line);
    background:var(--paper);
    color:var(--gold);
    cursor:pointer;
    transition:transform .08s ease, box-shadow .08s ease, background .12s;
  }
  button:hover{background:var(--sage);}
  button:active{transform:scale(.97);}
  button.primary{background:var(--gold);color:white;border-color:var(--gold);}
  button.primary:hover{background:var(--green-mid);}
  button.gold{background:var(--green-deep);color:white;border-color:var(--green-deep);}
  button.gold:hover{background:var(--green-mid);border-color:var(--green-mid);}
  .hint{
    font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;
    font-size:11px;
    color:var(--ink-soft);
  }

  /* ---------- Table ---------- */
  .table-wrap{
    padding:16px clamp(18px,4vw,56px) 0;
  }
  .scroller{
    overflow-x:auto;
    border:1px solid var(--line);
    border-radius:10px;
    background:var(--paper);
    box-shadow:0 10px 26px -20px rgba(30,69,54,.4);
  }
  table{
    border-collapse:collapse;
    width:100%;
    min-width: 100%;
  }
  thead th{
    position:sticky;
    top:0;
    z-index:3;
    background:var(--green-deep);
    color:var(--cream);
    font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;
    font-weight:500;
    font-size: 12.5px;
    letter-spacing:.05em;
    text-transform:uppercase;
    text-align:left;
    padding:12px 12px;
    border-right:1px solid rgba(250,247,240,.12);
    white-space:normal;
    vertical-align:bottom;
  }
  thead th .colnum{
    display:block;
    font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;
    font-style:italic;
    color:var(--gold-soft);
    font-size: 14px;
    margin-bottom:2px;
  }
  thead th em{font-style:normal;color:var(--gold-soft);display:block;font-size:9.5px;margin-top:3px;text-transform:none;letter-spacing:0;}
  th.col-num, td.col-num{width:34px;min-width:34px;text-align:center;}
  th.col-actions, td.col-actions{width:40px;min-width:40px;}

  tbody td{
    padding:11px 12px;
    border-right:1px solid var(--line);
    border-bottom:1px solid var(--line);
    vertical-align:top;
    font-size:13px;
    min-width: 120px;
  }
  tbody td[data-col="budget"], tbody td[data-col="cost"]{
    min-width: 180px;
  }
  tbody td[data-col="issue"]{min-width: 160px;}
  tbody td[data-col="indicators"], tbody td[data-col="actualResult"]{min-width: 160px;}
  tbody tr:nth-child(odd) td{background:#f8fafc;}
  tbody tr:hover td{background:var(--sage);}
  td[contenteditable="true"]{outline:none;white-space:pre-wrap;}
  td[contenteditable="true"]:focus{background:#f3e8ff !important;box-shadow:inset 0 0 0 2px var(--gold);}
  td.col-num{
    font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;
    color:var(--ink-soft);
    text-align:center;
    background:var(--sage) !important;
    font-weight:600;
  }
  .remove-btn{
    width:22px;height:22px;border-radius:50%;
    border:1px solid var(--line);background:var(--paper);
    color:var(--danger);font-size:13px;line-height:1;
    cursor:pointer;display:flex;align-items:center;justify-content:center;
  }
  .remove-btn:hover{background:var(--danger);color:var(--paper);border-color:var(--danger);}

  /* section divider rows */
  tr.section-row td{
    background:var(--gold) !important;
    color:var(--gold);
    font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;
    font-weight:700;
    font-size:13.5px;
    letter-spacing:.03em;
    text-transform:uppercase;
    padding:10px 14px;
    border-bottom:2px solid var(--green-deep);
  }
  tr.add-row td{
    background:var(--paper) !important;
    padding:10px 14px;
    border-bottom:1px solid var(--line);
  }
  tr.add-row button{width:100%;border-style:dashed;}

  /* ---------- line-item editors (budget / cost cells) ---------- */
  .items-editor{display:flex;flex-direction:column;}
  .item-line{
    display:flex;flex-direction:column;gap:3px;
    padding:6px 0;border-bottom:1px dashed var(--line);
  }
  .item-line:first-child{padding-top:0;}
  .item-label{
    width:100%;border:none;border-bottom:1px solid transparent;background:transparent;
    font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;font-size: 14px;color:var(--ink);padding:2px 1px;
  }
  .item-label::placeholder{color:#94a3b8;}
  .item-label:hover{border-bottom-color:var(--line);}
  .item-label:focus{border-bottom-color:var(--gold);outline:none;background:#f3e8ff;}
  .item-meta-row{display:flex;align-items:center;gap:6px;}
  .item-amt-wrap{display:flex;align-items:center;gap:2px;font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;font-size: 14px;color:var(--gold);}
  .peso{opacity:.65;}
  .item-amt{
    width:92px;border:none;border-bottom:1px solid transparent;background:transparent;
    font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;font-size: 14px;color:var(--gold);text-align:right;padding:2px 1px;
    -moz-appearance:textfield;
  }
  .item-amt::-webkit-outer-spin-button,.item-amt::-webkit-inner-spin-button{-webkit-appearance:none;margin:0;}
  .item-amt:hover{border-bottom-color:var(--line);}
  .item-amt:focus{border-bottom-color:var(--gold);outline:none;background:#f3e8ff;}
  .item-src{
    width:42px;border:none;border-bottom:1px dotted var(--line);background:transparent;
    font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;font-size:9.5px;color:var(--ink-soft);text-align:center;
    padding:1px 0;text-transform:uppercase;
  }
  .item-src:focus{outline:none;border-bottom-color:var(--gold);}
  .item-rm{
    margin-left:auto;width:18px;height:18px;border-radius:50%;flex:none;
    border:1px solid var(--line);background:var(--paper);color:var(--danger);font-size:11px;line-height:1;
    cursor:pointer;display:flex;align-items:center;justify-content:center;padding:0;
  }
  .item-rm:hover{background:var(--danger);color:#fff;border-color:var(--danger);}
  .item-add{
    margin-top:6px;width:100%;font-size: 12.5px;padding:5px 8px;border-style:dashed;
  }
  .item-subtotal{
    margin-top:9px;padding-top:8px;border-top:1px solid var(--ink);
    font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;font-weight:600;font-size: 14px;color:var(--gold);
  }
  .variance-badge{
    margin-top:7px;font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;font-size: 12px;
    padding:3px 7px;border-radius:5px;display:inline-block;font-weight:600;
  }
  .variance-badge.over{background:var(--danger-bg);color:var(--danger);}
  .variance-badge.under{background:var(--ok-bg);color:var(--ok);}
  .variance-badge.neutral{background:var(--sage);color:var(--ink-soft);}

  /* ---------- gender-split editors (indicators / actual result cells) ---------- */
  .gender-cell{display:flex;flex-direction:column;gap:0;}
  .gc-text{
    font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;font-size: 14.5px;color:var(--ink);
    white-space:pre-wrap;outline:none;padding:2px 1px;border-radius:4px;
  }
  .gc-text:hover{background:var(--sage);}
  .gc-text:focus{background:#f3e8ff;box-shadow:inset 0 0 0 1px var(--gold);}
  .gc-gender-row{
    display:flex;align-items:center;gap:12px;
    margin-top:8px;padding-top:7px;border-top:1px dashed var(--line);
  }
  .gc-field{display:flex;align-items:center;gap:4px;font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;font-size: 12.5px;color:var(--ink-soft);}
  .gc-field label{font-weight:700;color:var(--gold);}
  .gc-amt{
    width:48px;border:none;border-bottom:1px solid var(--line);background:transparent;
    font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;font-size: 14px;color:var(--gold);text-align:right;padding:2px 1px;
    -moz-appearance:textfield;
  }
  .gc-amt::-webkit-outer-spin-button,.gc-amt::-webkit-inner-spin-button{-webkit-appearance:none;margin:0;}
  .gc-amt:focus{outline:none;border-bottom-color:var(--gold);background:#f3e8ff;}
  .gc-amt::placeholder{color:#cbd5e1;}
  .gc-total{margin-left:auto;font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;font-size: 12px;color:var(--ink-soft);white-space:nowrap;}
  .gc-compare{
    margin-top:6px;font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;font-size: 12px;
    padding:3px 7px;border-radius:5px;display:inline-block;font-weight:600;
  }
  .gc-compare.positive{background:var(--ok-bg);color:var(--ok);}
  .gc-compare.negative{background:var(--danger-bg);color:var(--danger);}

  tfoot td{
    background:var(--green-deep) !important;
    color:var(--cream);
    font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;
    font-weight:600;
    font-size: 14.5px;
    padding:12px;
    border-top:2px solid var(--gold);
    vertical-align:middle;
  }
  tfoot tr.foot-computed td{background:var(--green-mid) !important;font-weight:500;}
  tfoot tr.foot-diff td{background:var(--ink) !important;font-weight:500;font-size: 13.5px;}
  tfoot tr.foot-gender td{background:var(--green-mid) !important;font-weight:500;font-size: 13.5px;}
  tfoot .foot-label{text-align:right;padding-right:18px;font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;letter-spacing:.03em;}
  tfoot td[contenteditable="true"]{outline:none;}
  tfoot td[contenteditable="true"]:focus{background:rgba(255,255,255,.12) !important;}

  /* ---------- Signature block ---------- */
  .sign-wrap{padding:34px clamp(18px,4vw,56px) 0;}
  .sign-panel{
    background:var(--paper);
    border:1px solid var(--line);
    border-radius:10px;
    padding:24px clamp(14px,3vw,28px);
    display:grid;
    grid-template-columns:1fr 1fr 1fr;
    gap:24px;
  }
  @media (max-width:720px){.sign-panel{grid-template-columns:1fr;}}
  .sign-block label{
    display:block;font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;font-size: 12.5px;
    letter-spacing:.08em;text-transform:uppercase;color:var(--ink-soft);margin-bottom:10px;
  }
  .sign-line{
    border-top:1px solid var(--ink);
    padding-top:8px;
    font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;
    font-weight:600;
    font-size:15px;
    min-height:1.4em;
    outline:none;
  }
  .sign-role{
    font-size: 13.5px;color:var(--ink-soft);margin-top:3px;
    font-style:italic;font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;
  }

  footer.page-foot{
    padding:26px clamp(18px,4vw,56px) 0;
    display:flex;justify-content:space-between;flex-wrap:wrap;gap:8px;
    font-family: 'Manrope', system-ui, Avenir, Helvetica, Arial, sans-serif;font-size: 12.5px;color:var(--ink-soft);
  }

  @media print{
    & { background: transparent !important; padding:0;background:#fff;}
    .toolbar, .add-row, .remove-btn, .col-actions, .item-rm, .item-add{display:none !important;}
    .masthead{border-bottom:3px solid #000;}
    .scroller{border:none;box-shadow:none;overflow:visible;}
    table{min-width:100%;}
    thead th{position:static;background:var(--green-deep) !important;-webkit-print-color-adjust:exact;print-color-adjust:exact;}
    tr.section-row td{-webkit-print-color-adjust:exact;print-color-adjust:exact;}
    tfoot td{-webkit-print-color-adjust:exact;print-color-adjust:exact;}
  }

.scroller {
    background: #0f172a !important; /* Dark Slate-900 */
    --paper: #0f172a;
    --ink: #f8fafc;
    --ink-soft: #cbd5e1;
    --line: #334155;
    --sage: #1e293b; /* Dark alternate row */
    --danger-bg: #450a0a;
    --ok-bg: #064e3b;
    color: var(--ink);
}
.scroller table { color: var(--ink); background: transparent !important; }
.scroller tbody td { background: transparent !important; }

/* Dark mode for info-panel */
.info-panel {
    background: #0f172a !important;
    border-color: #334155 !important;
    color: #f8fafc;
}
.info-panel .field label { color: #cbd5e1 !important; }
.info-panel .field .val { color: #f8fafc !important; }
.info-panel .field.stat .val { color: var(--gold-soft) !important; }
.info-panel .field.computed .val { 
    background: #1e293b !important; 
    color: var(--gold-soft) !important; 
}
.scroller tbody tr { background: #0f172a !important; }
.scroller tbody tr:hover { background: #1e293b !important; }
.scroller tbody tr:hover td { background: transparent !important; }
.scroller input, .scroller select, .scroller textarea, .scroller .editable, .scroller .val {
    color: var(--ink) !important;
    background: transparent !important;
}
.scroller thead th {
    background: #020617 !important; /* Slate-950 for headers */
    color: #e2e8f0 !important;
}
.scroller .td-inner:focus-within {
    background: #2e1065 !important; /* Dark purple focus */
    outline: 2px solid var(--gold);
}
.scroller .val:focus {
    background: #2e1065 !important;
}

}
</style>