<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_gestion_ingreso.enrollments', function (Blueprint $table) {
            $table->index(['academic_period_id', 'status'], 'idx_enrollments_period_status');
            $table->index(['status'], 'idx_enrollments_status');
        });

        Schema::table('app_gestion_ingreso.applicants', function (Blueprint $table) {
            $table->index(['status'], 'idx_applicants_status');
        });

        Schema::table('app_gestion_ingreso.payment_orders', function (Blueprint $table) {
            $table->index(['status'], 'idx_payment_orders_status');
        });

        Schema::table('app_gestion_ingreso.admission_processes', function (Blueprint $table) {
            $table->index(['status'], 'idx_admission_processes_status');
        });

        Schema::table('app_ensenanza_aprendizaje.class_sessions', function (Blueprint $table) {
            $table->index(['subject_id', 'academic_period_id', 'status'], 'idx_class_sessions_subject_period_status');
        });

        Schema::table('app_ensenanza_aprendizaje.student_evaluations', function (Blueprint $table) {
            $table->index(['academic_period_id', 'subject_id'], 'idx_student_evaluations_period_subject');
        });

        Schema::table('app_gestion_curricular.technical_reports', function (Blueprint $table) {
            $table->index(['status'], 'idx_technical_reports_status');
        });

        Schema::table('app_gestion_curricular.curriculum_reviews', function (Blueprint $table) {
            $table->index(['status'], 'idx_curriculum_reviews_status');
        });

        Schema::table('app_gestion_curricular.resource_requests', function (Blueprint $table) {
            $table->index(['status'], 'idx_resource_requests_status');
        });

        Schema::table('app_resultados_formacion.certificates', function (Blueprint $table) {
            $table->index(['issued_at'], 'idx_certificates_issued_at');
        });

        Schema::table('app_resultados_formacion.degree_applications', function (Blueprint $table) {
            $table->index(['application_date'], 'idx_degree_applications_application_date');
        });

        Schema::table('app_resultados_formacion.degree_committee_acts', function (Blueprint $table) {
            $table->index(['session_date'], 'idx_degree_committee_acts_session_date');
        });

        Schema::table('app_resultados_formacion.graduates', function (Blueprint $table) {
            $table->index(['work_status'], 'idx_graduates_work_status');
        });
    }

    public function down(): void
    {
        Schema::table('app_gestion_ingreso.enrollments', function (Blueprint $table) {
            $table->dropIndex('idx_enrollments_period_status');
            $table->dropIndex('idx_enrollments_status');
        });

        Schema::table('app_gestion_ingreso.applicants', function (Blueprint $table) {
            $table->dropIndex('idx_applicants_status');
        });

        Schema::table('app_gestion_ingreso.payment_orders', function (Blueprint $table) {
            $table->dropIndex('idx_payment_orders_status');
        });

        Schema::table('app_gestion_ingreso.admission_processes', function (Blueprint $table) {
            $table->dropIndex('idx_admission_processes_status');
        });

        Schema::table('app_ensenanza_aprendizaje.class_sessions', function (Blueprint $table) {
            $table->dropIndex('idx_class_sessions_subject_period_status');
        });

        Schema::table('app_ensenanza_aprendizaje.student_evaluations', function (Blueprint $table) {
            $table->dropIndex('idx_student_evaluations_period_subject');
        });

        Schema::table('app_gestion_curricular.technical_reports', function (Blueprint $table) {
            $table->dropIndex('idx_technical_reports_status');
        });

        Schema::table('app_gestion_curricular.curriculum_reviews', function (Blueprint $table) {
            $table->dropIndex('idx_curriculum_reviews_status');
        });

        Schema::table('app_gestion_curricular.resource_requests', function (Blueprint $table) {
            $table->dropIndex('idx_resource_requests_status');
        });

        Schema::table('app_resultados_formacion.certificates', function (Blueprint $table) {
            $table->dropIndex('idx_certificates_issued_at');
        });

        Schema::table('app_resultados_formacion.degree_applications', function (Blueprint $table) {
            $table->dropIndex('idx_degree_applications_application_date');
        });

        Schema::table('app_resultados_formacion.degree_committee_acts', function (Blueprint $table) {
            $table->dropIndex('idx_degree_committee_acts_session_date');
        });

        Schema::table('app_resultados_formacion.graduates', function (Blueprint $table) {
            $table->dropIndex('idx_graduates_work_status');
        });
    }
};
