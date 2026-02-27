#!/bin/bash

# 测试运行脚本

echo "=========================================="
echo "ThinkPHP8 Admin System - 测试套件"
echo "=========================================="

# 检查PHPUnit是否安装
if [ ! -f "vendor/bin/phpunit" ]; then
    echo "PHPUnit未安装，正在安装依赖..."
    composer install
fi

# 解析命令行参数
SUITE=""
COVERAGE=false
FILTER=""

while [[ $# -gt 0 ]]; do
    case $1 in
        --unit)
            SUITE="Unit"
            shift
            ;;
        --feature)
            SUITE="Feature"
            shift
            ;;
        --coverage)
            COVERAGE=true
            shift
            ;;
        --filter)
            FILTER="$2"
            shift 2
            ;;
        *)
            shift
            ;;
    esac
done

# 构建PHPUnit命令
CMD="./vendor/bin/phpunit"

if [ -n "$SUITE" ]; then
    CMD="$CMD --testsuite $SUITE"
fi

if [ -n "$FILTER" ]; then
    CMD="$CMD --filter $FILTER"
fi

if [ "$COVERAGE" = true ]; then
    CMD="$CMD --coverage-html tests/coverage"
fi

# 运行测试
echo ""
echo "执行命令: $CMD"
echo "------------------------------------------"
$CMD

# 显示测试统计
echo ""
echo "=========================================="
echo "测试完成！"
if [ "$COVERAGE" = true ]; then
    echo "覆盖率报告: tests/coverage/index.html"
fi
echo "=========================================="
echo ""
echo "使用说明:"
echo "  ./run_tests.sh              # 运行所有测试"
echo "  ./run_tests.sh --unit       # 仅运行单元测试"
echo "  ./run_tests.sh --feature    # 仅运行功能测试"
echo "  ./run_tests.sh --coverage   # 生成覆盖率报告"
echo "  ./run_tests.sh --filter TestName  # 运行指定测试"
echo ""
