#!/usr/bin/env node

/**
 * Helper script to set up Vercel environment variables
 * Run: node setup-vercel-env.js
 */

const { execSync } = require('child_process');
const readline = require('readline');

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout
});

function question(query) {
  return new Promise(resolve => rl.question(query, resolve));
}

async function setupEnv() {
  console.log('🚀 Vercel Environment Variables Setup\n');
  console.log('This script will help you set environment variables for your Vercel deployment.\n');

  // Required variables
  const envVars = {
    APP_KEY: {
      description: 'Application encryption key (REQUIRED)',
      generate: true,
      required: true
    },
    APP_ENV: {
      description: 'Application environment',
      default: 'production',
      required: true
    },
    APP_DEBUG: {
      description: 'Debug mode (true/false)',
      default: 'false',
      required: true
    },
    APP_URL: {
      description: 'Application URL (e.g., https://your-app.vercel.app)',
      required: true
    },
    DB_CONNECTION: {
      description: 'Database connection (mysql/pgsql)',
      default: 'mysql',
      required: true
    },
    DB_HOST: {
      description: 'Database host',
      required: true
    },
    DB_PORT: {
      description: 'Database port',
      default: '3306',
      required: true
    },
    DB_DATABASE: {
      description: 'Database name',
      required: true
    },
    DB_USERNAME: {
      description: 'Database username',
      required: true
    },
    DB_PASSWORD: {
      description: 'Database password',
      required: true
    },
    SESSION_DRIVER: {
      description: 'Session driver',
      default: 'database',
      required: false
    },
    CACHE_STORE: {
      description: 'Cache store',
      default: 'database',
      required: false
    },
    QUEUE_CONNECTION: {
      description: 'Queue connection',
      default: 'database',
      required: false
    }
  };

  const values = {};

  console.log('📝 Please provide the following information:\n');

  for (const [key, config] of Object.entries(envVars)) {
    let value;

    if (config.generate && key === 'APP_KEY') {
      console.log(`\n⚠️  ${key}: ${config.description}`);
      console.log('   Generating APP_KEY...');
      try {
        // Try to generate using PHP artisan
        const keyOutput = execSync('php artisan key:generate --show', { encoding: 'utf-8' });
        value = keyOutput.trim();
        console.log(`   ✅ Generated: ${value.substring(0, 20)}...`);
      } catch (error) {
        console.log('   ⚠️  Could not auto-generate. Please run: php artisan key:generate --show');
        value = await question(`   Enter ${key}: `);
      }
    } else {
      const prompt = config.default 
        ? `${key} (${config.description}) [${config.default}]: `
        : `${key} (${config.description}): `;
      
      const answer = await question(prompt);
      value = answer.trim() || config.default || '';
      
      if (config.required && !value) {
        console.log(`   ⚠️  ${key} is required!`);
        value = await question(`   Enter ${key}: `);
      }
    }

    values[key] = value;
  }

  rl.close();

  console.log('\n📋 Environment Variables to set in Vercel:\n');
  console.log('You can set these via:');
  console.log('1. Vercel Dashboard: https://vercel.com/francis-cruzs-projects/dental_schedule/settings/environment-variables');
  console.log('2. Vercel CLI: vercel env add <VARIABLE_NAME>\n');

  for (const [key, value] of Object.entries(values)) {
    console.log(`${key}=${value}`);
  }

  console.log('\n💡 Quick setup with Vercel CLI:');
  console.log('Run the following commands (replace values as needed):\n');
  
  for (const [key, value] of Object.entries(values)) {
    console.log(`vercel env add ${key} production`);
  }

  console.log('\n✅ After setting environment variables, redeploy:');
  console.log('vercel --prod\n');
}

setupEnv().catch(console.error);

