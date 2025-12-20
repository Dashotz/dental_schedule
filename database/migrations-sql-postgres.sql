-- Laravel Database Migrations SQL Script for PostgreSQL (Supabase)
-- Run this directly in Supabase SQL Editor to create required tables
-- This will allow Laravel to bootstrap properly

-- Sessions table (required for SESSION_DRIVER=database)
CREATE TABLE IF NOT EXISTS sessions (
  id varchar(255) NOT NULL,
  user_id bigint DEFAULT NULL,
  ip_address varchar(45) DEFAULT NULL,
  user_agent text DEFAULT NULL,
  payload text NOT NULL,
  last_activity integer NOT NULL,
  PRIMARY KEY (id)
);

CREATE INDEX IF NOT EXISTS sessions_user_id_index ON sessions (user_id);
CREATE INDEX IF NOT EXISTS sessions_last_activity_index ON sessions (last_activity);

-- Cache table (required for CACHE_STORE=database)
CREATE TABLE IF NOT EXISTS cache (
  key varchar(255) NOT NULL,
  value text NOT NULL,
  expiration integer NOT NULL,
  PRIMARY KEY (key)
);

-- Cache locks table (optional but recommended)
CREATE TABLE IF NOT EXISTS cache_locks (
  key varchar(255) NOT NULL,
  owner varchar(255) NOT NULL,
  expiration integer NOT NULL,
  PRIMARY KEY (key)
);

-- Jobs table (required for QUEUE_CONNECTION=database)
CREATE TABLE IF NOT EXISTS jobs (
  id bigserial PRIMARY KEY,
  queue varchar(255) NOT NULL,
  payload text NOT NULL,
  attempts smallint NOT NULL,
  reserved_at integer DEFAULT NULL,
  available_at integer NOT NULL,
  created_at integer NOT NULL
);

CREATE INDEX IF NOT EXISTS jobs_queue_index ON jobs (queue);

-- Job batches table (for queue batching)
CREATE TABLE IF NOT EXISTS job_batches (
  id varchar(255) NOT NULL,
  name varchar(255) NOT NULL,
  total_jobs integer NOT NULL,
  pending_jobs integer NOT NULL,
  failed_jobs integer NOT NULL,
  failed_job_ids text NOT NULL,
  options text DEFAULT NULL,
  cancelled_at integer DEFAULT NULL,
  created_at integer NOT NULL,
  finished_at integer DEFAULT NULL,
  PRIMARY KEY (id)
);

-- Failed jobs table
CREATE TABLE IF NOT EXISTS failed_jobs (
  id bigserial PRIMARY KEY,
  uuid varchar(255) NOT NULL UNIQUE,
  connection text NOT NULL,
  queue text NOT NULL,
  payload text NOT NULL,
  exception text NOT NULL,
  failed_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Users table (if not exists)
CREATE TABLE IF NOT EXISTS users (
  id bigserial PRIMARY KEY,
  name varchar(255) NOT NULL,
  email varchar(255) NOT NULL UNIQUE,
  email_verified_at timestamp DEFAULT NULL,
  password varchar(255) NOT NULL,
  role varchar(255) NOT NULL DEFAULT 'staff' CHECK (role IN ('admin', 'doctor', 'staff')),
  remember_token varchar(100) DEFAULT NULL,
  created_at timestamp DEFAULT NULL,
  updated_at timestamp DEFAULT NULL
);

-- Migrations table (to track which migrations have run)
CREATE TABLE IF NOT EXISTS migrations (
  id serial PRIMARY KEY,
  migration varchar(255) NOT NULL,
  batch integer NOT NULL
);

