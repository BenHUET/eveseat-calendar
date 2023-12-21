<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('calendar_settings')) {

            $settings = DB::table('calendar_settings')->first();

            if (!is_null($settings)) {

                if (Schema::hasColumn('calendar_settings', 'slack_integration')) {
                    setting([
                        'kassie.calendar.slack_integration',
                        $settings->slack_integration,
                    ], true);
                }

                if (Schema::hasColumn('calendar_settings', 'slack_webhook')) {
                    setting([
                        'kassie.calendar.slack_webhook',
                        $settings->slack_webhook,
                    ], true);
                }

                if (Schema::hasColumn('calendar_settings', 'slack_emoji_importance_full')) {
                    setting([
                        'kassie.calendar.slack_emoji_importance_full',
                        $settings->slack_emoji_importance_full,
                    ], true);
                }

                if (Schema::hasColumn('calendar_settings', 'slack_emoji_importance_half')) {
                    setting([
                        'kassie.calendar.slack_emoji_importance_half',
                        $settings->slack_emoji_importance_half,
                    ], true);
                }

                if (Schema::hasColumn('calendar_settings', 'slack_emoji_importance_empty')) {
                    setting([
                        'kassie.calendar.slack_emoji_importance_empty',
                        $settings->slack_emoji_importance_empty,
                    ], true);
                }

            }

            Schema::drop('calendar_settings');

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        if (!Schema::hasTable('calendar_settings')) {

            Schema::create('calendar_settings', function (Blueprint $table): void {
                $table->increments('id');
                $table->boolean('slack_integration');
                $table->string('slack_webhook');
                $table->string('slack_emoji_importance_full');
                $table->string('slack_emoji_importance_half');
                $table->string('slack_emoji_importance_empty');
            });

        }

        if (!Schema::hasColumn('calendar_settings', 'slack_integration')) {
            Schema::table('calendar_settings', function(Blueprint $table): void {
                $table->boolean('slack_integration')->first();
            });
        }

        if (!Schema::hasColumn('calendar_settings', 'slack_webhook')) {
            Schema::table('calendar_settings', function(Blueprint $table): void {
                $table->string('slack_webhook')->after('slack_integration');
            });
        }

        if (!Schema::hasColumn('calendar_settings', 'slack_emoji_importance_full')) {
            Schema::table('calendar_settings', function(Blueprint $table): void {
                $table->string('slack_emoji_importance_full')->after('slack_webhook');
            });
        }

        if (!Schema::hasColumn('calendar_settings', 'slack_emoji_importance_half')) {
            Schema::table('calendar_settings', function(Blueprint $table): void {
                $table->string('slack_emoji_importance_half')->after('slack_emoji_importance_full');
            });
        }

        if (!Schema::hasColumn('calendar_settings', 'slack_emoji_importance_empty')) {
            Schema::table('calendar_settings', function(Blueprint $table): void {
                $table->string('slack_emoji_importance_empty')->after('slack_emoji_importance_half');
            });
        }

        $settings['slack_integration'] = setting('kassie.calendar.slack_integration', true);
        if (is_null($settings['slack_integration']))
            $settings['slack_integration'] = 0;

        $settings['slack_webhook'] = setting('kassie.calendar.slack_webhook', true) ?: '';

        $settings['slack_emoji_importance_full'] = setting('kassie.calendar.slack_emoji_importance_full', true) ?: '';

        $settings['slack_emoji_importance_half'] = setting('kassie.calendar.slack_emoji_importance_half', true) ?: '';

        $settings['slack_emoji_importance_empty'] = setting('kassie.calendar.slack_emoji_importance_empty', true) ?: '';

        DB::table('calendar_settings')->insert($settings);
    }
}
