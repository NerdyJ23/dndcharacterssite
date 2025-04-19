using DndCharacterSheets.Common.Infrastructure.Context.DndSheets;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Configuration;
using Microsoft.Extensions.DependencyInjection;

namespace DndCharacterSheets.Common.Infrastructure;

public static class ConfigureDatabases
{
	public static IServiceCollection AddDatabases(this IServiceCollection services, IConfiguration config)
	{
		services.AddDbContextFactory<DndSheetContext>(options =>
			options.UseMySQL(config.GetConnectionString("DndSheets") ?? throw new InvalidOperationException("Failed to find connection string for DndSheets")).EnableDetailedErrors().EnableSensitiveDataLogging()
		);

		return services;
	}
}