/**
 * Utility for Smart Office Checking & Validation
 * Detects existing office matches (exact, acronym, keywords, typos)
 * and warns against abbreviations.
 */

// Common university stop words that don't differentiate an office
const STOP_WORDS = new Set([
  'college', 'of', 'and', 'the', 'office', 'department', 'center', 'centre',
  'services', 'service', 'unit', 'bsu', 'campus', 'division', 'bureau'
]);

/**
 * Generate potential acronyms from an office name
 * e.g., "College of Agriculture" -> ["CA", "COA"]
 * "College of Information Sciences" -> ["CIS", "COIS"]
 */
export function generateAcronyms(officeName) {
  if (!officeName) return [];
  const words = officeName.trim().split(/\s+/).filter(Boolean);
  if (words.length <= 1) return [];

  // 1. Initial letters of major words (excluding stop words)
  const majorWords = words.filter(w => !['of', 'and', 'the', 'for', 'in', 'at', '&'].includes(w.toLowerCase()));
  const majorAcronym = majorWords.map(w => w[0]?.toUpperCase()).join('');

  // 2. Initial letters of ALL words
  const allAcronym = words.map(w => w[0]?.toUpperCase()).join('');

  const acronyms = new Set();
  if (majorAcronym.length >= 2) acronyms.add(majorAcronym);
  if (allAcronym.length >= 2) acronyms.add(allAcronym);

  return Array.from(acronyms);
}

/**
 * Normalize an office name to its core keywords
 * e.g., "College of Agriculture" -> "agriculture"
 * "BSU Office of Student Services" -> "student"
 */
export function extractCoreKeywords(name) {
  if (!name) return [];
  const clean = name.toLowerCase().replace(/[^a-z0-9\s]/g, ' ');
  const words = clean.split(/\s+/).filter(Boolean);
  return words.filter(w => !STOP_WORDS.has(w) && w.length > 2);
}

/**
 * Calculate Levenshtein distance between two strings
 */
function levenshteinDistance(a, b) {
  const m = a.length;
  const n = b.length;
  if (m === 0) return n;
  if (n === 0) return m;

  const dp = Array.from({ length: m + 1 }, () => new Array(n + 1).fill(0));

  for (let i = 0; i <= m; i++) dp[i][0] = i;
  for (let j = 0; j <= n; j++) dp[0][j] = j;

  for (let i = 1; i <= m; i++) {
    for (let j = 1; j <= n; j++) {
      const cost = a[i - 1] === b[j - 1] ? 0 : 1;
      dp[i][j] = Math.min(
        dp[i - 1][j] + 1,      // deletion
        dp[i][j - 1] + 1,      // insertion
        dp[i - 1][j - 1] + cost // substitution
      );
    }
  }

  return dp[m][n];
}

/**
 * Similarity ratio between 0 and 1
 */
export function stringSimilarity(str1, str2) {
  const s1 = (str1 || '').toLowerCase().trim();
  const s2 = (str2 || '').toLowerCase().trim();
  if (s1 === s2) return 1;
  const maxLen = Math.max(s1.length, s2.length);
  if (maxLen === 0) return 1;
  const distance = levenshteinDistance(s1, s2);
  return 1 - distance / maxLen;
}

/**
 * Detect if input looks like an abbreviation or acronym
 */
export function isAbbreviationPattern(input) {
  if (!input) return false;
  const trimmed = input.trim();
  if (!trimmed) return false;

  // Dots pattern like "C.A.", "O.S.S."
  if (/^[a-zA-Z](\.[a-zA-Z])+\.?$/.test(trimmed)) {
    return true;
  }

  // Very short strings without spaces (4 characters or fewer) e.g. "CA", "CTE", "CIS", "HR", "DEPT"
  if (trimmed.length <= 4 && !trimmed.includes(' ')) {
    return true;
  }

  // Common abbreviation slang/tokens
  const words = trimmed.toLowerCase().split(/\s+/);
  const commonAbbrevs = ['dept', 'dept.', 'off', 'off.', 'coll', 'coll.', 'admin', 'univ', 'acct', 'tech', 'chet', 'hrmo', 'spmo', 'drrm', 'ovpaa', 'ovpf', 'ovpre', 'cas', 'cte', 'cis', 'cn', 'cpag', 'chs', 'cvm', 'ca'];
  if (words.some(w => commonAbbrevs.includes(w))) {
    return true;
  }

  // All uppercase single word of length <= 6 (e.g. "CAS", "SPMO", "HRMO")
  if (trimmed === trimmed.toUpperCase() && trimmed.length <= 6 && !trimmed.includes(' ')) {
    return true;
  }

  return false;
}

/**
 * Smart Office Checker: Compares user input against the office list
 * @param {string} inputName - The typed office name
 * @param {Array} officeList - Array of office objects { unit_id, unit_name, office_acronym, location }
 * @param {string} currentCampus - Optional campus to prioritize
 * @returns {Object} validation result
 */
export function checkOfficeName(inputName, officeList = [], currentCampus = '') {
  const rawInput = (inputName || '').trim();
  if (!rawInput) {
    return {
      isEmpty: true,
      isValid: false,
      hasMatch: false,
      matchedOffice: null,
      matchReason: null,
      isAbbreviation: false,
      message: ''
    };
  }

  const normalizedInput = rawInput.toLowerCase();
  const cleanInputAcronym = rawInput.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
  const inputKeywords = extractCoreKeywords(rawInput);
  const isAbbrev = isAbbreviationPattern(rawInput);

  // Filter or prioritize by campus if provided
  const candidateOffices = Array.isArray(officeList) ? officeList : [];

  // 1. Check EXACT match (case-insensitive & trimmed)
  const exactMatch = candidateOffices.find(o => 
    (o.unit_name && o.unit_name.trim().toLowerCase() === normalizedInput)
  );
  if (exactMatch) {
    return {
      isEmpty: false,
      isValid: false,
      hasMatch: true,
      matchedOffice: exactMatch,
      matchReason: 'exact',
      isAbbreviation: isAbbrev,
      message: `"${exactMatch.unit_name}" is already in the list.`
    };
  }

  // 2. Check ACRONYM match
  // e.g. User enters "CA", "CTE", "CIS", "HRMO", "C.A."
  const acronymMatch = candidateOffices.find(o => {
    // Check official office_acronym
    if (o.office_acronym) {
      const cleanOfficialAcronym = o.office_acronym.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
      if (cleanOfficialAcronym === cleanInputAcronym) return true;
    }
    // Check computed acronyms from full office name
    const generated = generateAcronyms(o.unit_name);
    return generated.some(ac => ac === cleanInputAcronym);
  });

  if (acronymMatch) {
    return {
      isEmpty: false,
      isValid: false,
      hasMatch: true,
      matchedOffice: acronymMatch,
      matchReason: 'acronym',
      isAbbreviation: true,
      message: `"${rawInput}" appears to be an acronym for "${acronymMatch.unit_name}".`
    };
  }

  // 3. Check CORE KEYWORD & SUBSTRING match
  // e.g. User enters "Accounting" when "Accounting Office" exists
  // Or "Agriculture" when "College of Agriculture" exists
  const substringMatch = candidateOffices.find(o => {
    const oName = (o.unit_name || '').toLowerCase();
    
    // Substring containment if input is substantive (>= 4 chars)
    if (rawInput.length >= 4) {
      if (oName.includes(normalizedInput)) return true;
      if (normalizedInput.includes(oName)) return true;
    }

    // Core keywords overlap
    if (inputKeywords.length > 0) {
      const officeKeywords = extractCoreKeywords(o.unit_name);
      if (officeKeywords.length > 0) {
        // If ALL input keywords match the office keywords (e.g., "veterinary medicine")
        const allKeywordsMatch = inputKeywords.every(kw => officeKeywords.includes(kw));
        if (allKeywordsMatch && inputKeywords.length >= 1) return true;
      }
    }
    return false;
  });

  if (substringMatch) {
    return {
      isEmpty: false,
      isValid: false,
      hasMatch: true,
      matchedOffice: substringMatch,
      matchReason: 'keyword',
      isAbbreviation: isAbbrev,
      message: `Did you mean "${substringMatch.unit_name}"? It is already in the list.`
    };
  }

  // 4. Check FUZZY / TYPO match (> 82% similarity)
  let bestFuzzyMatch = null;
  let highestSimilarity = 0;

  for (const o of candidateOffices) {
    const sim = stringSimilarity(o.unit_name, rawInput);
    if (sim > 0.82 && sim > highestSimilarity) {
      highestSimilarity = sim;
      bestFuzzyMatch = o;
    }
  }

  if (bestFuzzyMatch) {
    return {
      isEmpty: false,
      isValid: false,
      hasMatch: true,
      matchedOffice: bestFuzzyMatch,
      matchReason: 'fuzzy',
      isAbbreviation: isAbbrev,
      message: `Did you mean "${bestFuzzyMatch.unit_name}"?`
    };
  }

  // 5. If NO match, but it's an abbreviation (e.g. <= 4 letters or all caps)
  if (isAbbrev) {
    return {
      isEmpty: false,
      isValid: false,
      hasMatch: false,
      matchedOffice: null,
      matchReason: null,
      isAbbreviation: true,
      message: `"${rawInput}" appears to be an abbreviation. Please type the full official office name without abbreviations.`
    };
  }

  // 6. Valid, unique, full office name
  return {
    isEmpty: false,
    isValid: true,
    hasMatch: false,
    matchedOffice: null,
    matchReason: null,
    isAbbreviation: false,
    message: `"${rawInput}" is unique and ready to be registered.`
  };
}
