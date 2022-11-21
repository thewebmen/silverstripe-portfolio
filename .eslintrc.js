module.exports = {
  env: {
    browser: true,
    es2021: true,
  },
  extends: [
    'airbnb-base',
  ],
  parserOptions: {
    ecmaVersion: 12,
    sourceType: 'module',
  },
  ignorePatterns: [
    '.eslintrc.js',
    '**/*.config.js',
    '**/vendor/*.js',
  ],
  rules: {
    'no-new': 0,
    'max-len': 0,
    'no-console': 0,
    'prefer-template': 0,
  },
};
