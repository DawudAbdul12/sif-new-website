var SIF_PROJECTS = [
  {
    id:"gwyesco",
    name:"GWYESCO",
    fullName:"Ghana Women & Youth Employment and Social Cohesion Programme",
    status:"new",
    statusLabel:"New · Launched 2026",
    category:["Employment","Women and Youth"],
    zone:"a",
    zoneName:"Savannah Belt",
    regions:["Northern","Upper East","Upper West","North East","Savannah","Nationwide"],
    timeline:"2026 – 2028",
    funder:"African Development Bank (AfDB)",
    fundAmount:"US$71.25M grant",
    image:"https://sifinghana.org/backend/images/uploads/SIF%20NEW%20PROJECT.jpg1750682497.jpg",
    summary:"Ghana's first AfDB results-based financing operation: training 30,000+ women and young people in STEM, digital and creative skills, expanding MSME finance access, and rebuilding social cohesion in northern Ghana.",
    objectives:[
      "Equip 30,000+ women and young people with STEM, digital and TVET skills.",
      "Expand access to MSME finance for women- and youth-led enterprises.",
      "Strengthen social cohesion and resilience in conflict-affected communities in northern Ghana.",
      "Establish Ghana's first AfDB results-based financing (RBF) operation as a model for future programmes."
    ],
    beneficiaries:"30,000+ women and young people (target), with priority given to communities in northern Ghana",
    outcomes:[
      "Ghana's first AfDB results-based financing operation, disbursed against verified results.",
      "Ministry of Finance is the executing agency; SIF is the implementing agency.",
      "Programme runs 2026–2028 across STEM, digital, TVET training and enterprise finance tracks."
    ],
    docs:[{label:"Programme Launch — myjoyonline.com", url:"https://www.myjoyonline.com/ghana-launches-landmark-women-and-youth-employment-programme-to-create-over-30000-jobs/"}],
    related:["psdpep","irdp2"],
    markers:[{lat:9.4035,lng:-0.8393,city:"Tamale, Northern Region"}]
  },
  {
    id:"psdpep",
    name:"PSDPEP",
    fullName:"Post-COVID-19 Skills Development & Productivity Enhancement Project",
    status:"ongoing",
    statusLabel:"Ongoing · 2023 – 2027",
    category:["Infrastructure","Employment","Community Development"],
    zone:"d",
    zoneName:"Eastern Seaboard",
    regions:["Greater Accra","Northern","Western","Nationwide"],
    timeline:"2023 – 2027",
    funder:"AfDB (US$28.5M) + Government of Ghana (US$2.8M)",
    fundAmount:"US$31.3M total",
    image:"https://sifinghana.org/backend/images/uploads/PSDPEP%20scholar.jpg%20web.jpg1743597962.jpg",
    summary:"Rehabilitating health and education infrastructure and putting low-interest microcredit into the hands of women- and youth-led MSMEs, in partnership with the Ghana News Agency, MASLOC and the University of Ghana.",
    objectives:[
      "Rehabilitate Ghana News Agency regional offices in Accra, Tema, Tamale and Takoradi.",
      "Deliver a US$4M MSME microcredit facility for women- and youth-led enterprises.",
      "Strengthen productivity and post-pandemic recovery in partnership with MASLOC and the University of Ghana.",
      "Track results against agreed indicators through to project close in 2027."
    ],
    beneficiaries:"Women- and youth-led MSMEs, GNA staff, and surrounding communities across four regional hubs",
    outcomes:[
      "GNA office rehabilitation under way in Accra, Tema, Tamale and Takoradi.",
      "US$4M microcredit facility established for MSME access to finance.",
      "Delivered in partnership with the Ghana News Agency, MASLOC and the University of Ghana."
    ],
    docs:[{label:"BADEA Appraisal Mission — Official Update", url:"https://sifinghana.org/page.php?id=3278"}],
    related:["gwyesco","irdp2"],
    markers:[
      {lat:5.6037,lng:-0.1870,city:"Accra, Greater Accra"},
      {lat:5.6698,lng:-0.0166,city:"Tema, Greater Accra"},
      {lat:9.4035,lng:-0.8393,city:"Tamale, Northern Region"},
      {lat:4.9047,lng:-1.7554,city:"Takoradi, Western Region"}
    ]
  },
  {
    id:"irdp2",
    name:"IRDP II",
    fullName:"Integrated Rural Development Project, Phase II",
    status:"ongoing",
    statusLabel:"Ongoing",
    category:["Infrastructure","Community Development"],
    zone:"b",
    zoneName:"Forest & Transition",
    regions:["Ashanti","Nationwide rural districts"],
    timeline:"Ongoing",
    funder:"OPEC Fund for International Development (OFID)",
    fundAmount:"OFID-financed",
    image:"https://sifinghana.org/backend/images/uploads/afigya%20kwabre%20irdp%202.jpg1742825153.jpg",
    summary:"The second phase of integrated rural development — extending socio-economic infrastructure and livelihood support to underserved rural districts nationwide, including Afigya Kwabre in the Ashanti Region.",
    objectives:[
      "Extend socio-economic infrastructure to underserved rural districts.",
      "Build on the foundation and lessons of IRDP I.",
      "Strengthen livelihoods alongside infrastructure delivery."
    ],
    beneficiaries:"Rural communities across multiple districts, including Afigya Kwabre, Ashanti Region",
    outcomes:[
      "Active infrastructure works recorded in Afigya Kwabre, Ashanti Region.",
      "Continued rollout nationwide, subject to OFID fund-release schedules."
    ],
    docs:[],
    related:["irdp1","psdpep"],
    markers:[{lat:6.85,lng:-1.55,city:"Afigya Kwabre, Ashanti Region"}]
  },
  {
    id:"irdp1",
    name:"IRDP I",
    fullName:"Integrated Rural Development Project, Phase I",
    status:"completed",
    statusLabel:"Completed",
    category:["Infrastructure","Employment"],
    zone:"b",
    zoneName:"Forest & Transition",
    regions:["Nationwide — 21 districts"],
    timeline:"Completed",
    funder:"Development Partners & Government of Ghana",
    fundAmount:"—",
    image:"https://sifinghana.org/backend/images/uploads/IRDP1.png1741004874.png",
    summary:"Rural development delivered across 21 districts — creating more than 2,000 employment opportunities for rural communities and laying the operating model IRDP II builds on.",
    objectives:[
      "Deliver socio-economic infrastructure across 21 rural districts.",
      "Generate sustainable employment opportunities for rural communities.",
      "Establish the integrated rural development model later scaled in IRDP II."
    ],
    beneficiaries:"Rural communities across 21 districts nationwide",
    outcomes:[
      "More than 2,000 employment opportunities created across 21 districts.",
      "Programme completed and succeeded by IRDP II."
    ],
    docs:[],
    related:["irdp2","gprp"],
    markers:[{lat:7.5833,lng:-1.9333,city:"Techiman, Bono East Region"}]
  },
  {
    id:"uprp",
    name:"UPRP",
    fullName:"Urban Poverty Reduction Project",
    status:"completed",
    statusLabel:"Completed",
    category:["Infrastructure","Community Development"],
    zone:"d",
    zoneName:"Eastern Seaboard",
    regions:["Greater Accra","Urban centres nationwide"],
    timeline:"Completed",
    funder:"Development Partners & Government of Ghana",
    fundAmount:"—",
    image:"https://sifinghana.org/backend/images/uploads/_ENN6076.jpg1738337029.jpg",
    summary:"Targeted infrastructure and services delivered to Ghana's most deprived urban neighbourhoods — laying the template for SIF's urban work today.",
    objectives:[
      "Deliver targeted infrastructure to deprived urban neighbourhoods.",
      "Improve access to basic services in low-income urban communities.",
      "Establish an urban delivery template for future SIF programmes."
    ],
    beneficiaries:"Residents of deprived urban neighbourhoods nationwide",
    outcomes:[
      "Programme completed; informed SIF's current urban-facing project design."
    ],
    docs:[],
    related:["gprp","psdpep"],
    markers:[{lat:5.6037,lng:-0.1870,city:"Accra, Greater Accra"}]
  },
  {
    id:"gprp",
    name:"GPRP I & II",
    fullName:"Ghana Poverty Reduction Project, Phases I & II",
    status:"completed",
    statusLabel:"Completed",
    category:["Infrastructure"],
    zone:"d",
    zoneName:"Eastern Seaboard",
    regions:["Nationwide"],
    timeline:"Completed",
    funder:"AfDB (Phase I) & OFID (Phase II)",
    fundAmount:"—",
    image:"https://sifinghana.org/backend/images/uploads/badea%207.jpg1739437245.jpg",
    summary:"The programme that built SIF's foundation — two phases of poverty-reduction infrastructure delivered nationwide since incorporation in 1998.",
    objectives:[
      "Deliver foundational poverty-reduction infrastructure nationwide.",
      "Establish SIF's earliest partnerships with AfDB and OFID."
    ],
    beneficiaries:"Communities nationwide, since SIF's incorporation in 1998",
    outcomes:[
      "Phase I delivered with AfDB financing; Phase II delivered with OFID financing.",
      "Foundational programme for all SIF work that followed."
    ],
    docs:[],
    related:["irdp1","uprp"],
    markers:[
      {lat:5.6037,lng:-0.1870,city:"Accra, Greater Accra"},
      {lat:9.4035,lng:-0.8393,city:"Tamale, Northern Region"}
    ]
  }
];

var ZONE_COLORS = {a:"#D6A72C", b:"#087F5B", c:"#4F6F52", d:"#073B2A"};
var ZONE_LABELS = {a:"Savannah Belt", b:"Forest & Transition", c:"Western Coast", d:"Eastern Seaboard"};
