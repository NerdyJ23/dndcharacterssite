using DndCharacterSheets.Common.Infrastructure;
using DndCharacterSheets.Common.Infrastructure.Context.DndSheets;
using Microsoft.EntityFrameworkCore;
using Serilog;

var builder = WebApplication.CreateBuilder(args);
string projectName = "DndSheets";
Environment.SetEnvironmentVariable("PROJECTNAME", projectName); // This sets the log file directory
Environment.SetEnvironmentVariable("STARTTIME", DateTime.Now.ToString("yyyyMMdd-HHmmss")); // This is to force a new log file on startup
Log.Logger = new LoggerConfiguration().CreateBootstrapLogger();

//Add Services
builder.Services.AddOpenApi();
builder.Host.UseSerilog((context, configuration) =>
{
	configuration.ReadFrom.Configuration(context.Configuration);
	Environment.SetEnvironmentVariable("ASPNETCORE_ENVIRONMENT", context.HostingEnvironment.EnvironmentName);
});

builder.Services.AddDatabases(builder.Configuration);
var app = builder.Build();

// Configure the HTTP request pipeline.
if (app.Environment.IsDevelopment())
{
	app.MapOpenApi();
	app.UseSwaggerUi(options => options.DocumentPath = "/openapi/v1.json");
}

app.UseHttpsRedirection();

using var scope = app.Services.CreateScope();
var factory = scope.ServiceProvider.GetRequiredService<IDbContextFactory<DndSheetContext>>();
using var context = factory.CreateDbContext();
context.Database.EnsureDeleted();
context.Database.EnsureCreated();

var summaries = new[]
{
	"Freezing", "Bracing", "Chilly", "Cool", "Mild", "Warm", "Balmy", "Hot", "Sweltering", "Scorching"
};

app.MapGet("/weatherforecast", () =>
{
	var forecast = Enumerable.Range(1, 5).Select(index =>
		new WeatherForecast
		(
			DateOnly.FromDateTime(DateTime.Now.AddDays(index)),
			Random.Shared.Next(-20, 55),
			summaries[Random.Shared.Next(summaries.Length)]
		))
		.ToArray();
	return forecast;
})
.WithName("GetWeatherForecast");

try
{
	await app.RunAsync();
}
finally
{
	await Log.CloseAndFlushAsync();
}

record WeatherForecast(DateOnly Date, int TemperatureC, string? Summary)
{
	public int TemperatureF => 32 + (int)(TemperatureC / 0.5556);
}
